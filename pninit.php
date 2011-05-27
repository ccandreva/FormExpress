<?php
/**
 * FormExpress : Build forms for Zikula through a web interface
 *
 * @copyright (c) 2002 Stutchbury Limited, 2011 Chris Candreva
 * @Version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package FormExpress
 *
 *
 * Origianally written by Philip Fletcher for PostNuke
 * Updated for Zikula API by Christopher X. Candreva
 *
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WIthOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * To read the license please visit http: *www.gnu.org/copyleft/gpl.html
 * ----------------------------------------------------------------------
 * Purpose of file:  Initialisation functions for FormExpress
 * ----------------------------------------------------------------------
 */

/**
 * initialise the FormExpress module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function FormExpress_init()
{
    
    if ( !DBUtil::createTable('FormExpress') ) return false;
    if ( !DBUtil::createTable('FormExpressItem') ) return false;
    if ( !DBUtil::createTable('FormExpressCache') ) return false;
    
    // Initialize module variables
    pnModSetVar('FormExpress', 'default_form_id', -1);
    pnModSetVar('FormExpress', 'allow_dynamic_syntax', 1);
    pnModSetVar('FormExpress', 'modulestylesheet', 'style.css');

    // Initialisation successful
    return true;
}

/**
 * upgrade the FormExpress module from an old version
 * This function can be called multiple times
 */
function FormExpress_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    //This is actually the new version number after the module list has been regenerated!!!!
    switch($oldversion) { 
        case '0.3.0':

            // Update table structures
            if ( !DBUtil::changeTable('FormExpress') ) return false;
            if ( !DBUtil::changeTable('FormExpressItem') ) return false;

            $pntable = pnDBGetTables();
            $FormExpresstable = $pntable['FormExpress'];
            $FormExpresscolumn = &$pntable['FormExpress_column'];

            //Populate the new columns
            $sql = "UPDATE $FormExpresstable
                       SET fx_submit_action = 
                           concat( '" .pnVarPrepForStore("[FormExpress->sendmail('email_address' => '")  ."'
                                 , fx_action_args 
                                 , '" .pnVarPrepForStore( "' )] ") ." '
                                 )
                         , fx_success_action = 
                           concat( '" .pnVarPrepForStore("[FormExpress->display_message('type' => 'success', 'message' => '") ."'
                                 , fx_success_message 
                                 , '" .pnVarPrepForStore( "' )] ") ." '
                                 )
                         , fx_failure_action = 
                           concat( '" .pnVarPrepForStore("[FormExpress->display_message('type' => 'failure', 'message' => '") ."'
                                 , fx_failure_message 
                                 , '" .pnVarPrepForStore( "' )] ") ." '
                                 )
                    ";
            $result = DBUtil::executeSQL($sql);

            //Create the cache table
            if ( !DBUtil::createTable('FormExpressCache') ) return false;

            pnModSetVar('FormExpress', 'default_form_id', 1);
            pnModSetVar('FormExpress', 'allow_dynamic_syntax', 1);
            pnModSetVar('FormExpress', 'modulestylesheet', 'style.css');

            break;

        case '0.3.5':
        case '0.4.0':
            /** The module style sheet wasn't originally set in 0.4.0,
             *  so set it just in case
             **/
            pnModSetVar('FormExpress', 'modulestylesheet', 'style.css');
            
    }

    // Update successful
    return true;
}

/**
 * delete the FormExpress module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function FormExpress_delete()
{
    DBUtil::dropTable('FormExpress');
    DBUtil::dropTable('FormExpressItem');
    DBUtil::dropTable('FormExpressCache');

    // Delete any module variables
    pnModDelVar('FormExpress', 'default_form_id', 1);
    pnModDelVar('FormExpress', 'allow_dynamic_syntax', 1);
    pnModDelVar('FormExpress', 'modulestylesheet', 'style.css');

    // Deletion successful
    return true;
}

?>
