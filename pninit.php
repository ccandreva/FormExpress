<?php
// $Id$
// ----------------------------------------------------------------------
// FormExpress module for POST-NUKE Content Management System
// Copyright (C) 2002 by Stutchbury Limited
// http://www.stutchbury.net/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Philip Fletcher
// Purpose of file:  Initialisation functions for FormExpress
// ----------------------------------------------------------------------

/**
 * initialise the FormExpress module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function FormExpress_init()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $FormExpresstable = $pntable['FormExpress'];
    $FormExpresscolumn = &$pntable['FormExpress_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "CREATE TABLE $FormExpresstable 
              ( $FormExpresscolumn[form_id] int(10) NOT NULL auto_increment
              , $FormExpresscolumn[form_name] varchar(50) NOT NULL
              , $FormExpresscolumn[description] text
              , $FormExpresscolumn[submit_action] text
              , $FormExpresscolumn[success_action] text
              , $FormExpresscolumn[failure_action] text
              , $FormExpresscolumn[onload_action] text
              , $FormExpresscolumn[validation_action] text
              , $FormExpresscolumn[active] tinyint(4) NOT NULL default 0
              , $FormExpresscolumn[language] varchar(30) NOT NULL
              , $FormExpresscolumn[input_name_suffix] varchar(30) NOT NULL
              , PRIMARY KEY( fx_form_id )
              )";
              //, PRIMARY KEY(fx_form_id)
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED . $sql);
        return false;
    }

    // It's good practice to name the table and column definitions you
    // are getting - $table and $column don't cut it in more complex
    // modules
    $FormExpressItemtable = $pntable['FormExpressItem'];
    $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "CREATE TABLE $FormExpressItemtable 
              ( $FormExpressItemcolumn[form_item_id] int(11) NOT NULL auto_increment
              , $FormExpressItemcolumn[form_id] int(11) NOT NULL
              , $FormExpressItemcolumn[sequence] int(11) NOT NULL
              , $FormExpressItemcolumn[item_type] varchar(15) NOT NULL default ''
              , $FormExpressItemcolumn[item_name] varchar(30) NOT NULL default ''
              , $FormExpressItemcolumn[required] tinyint(4) NOT NULL default 0
              , $FormExpressItemcolumn[prompt] varchar(100)
              , $FormExpressItemcolumn[prompt_position] varchar(10) default 'left'
              , $FormExpressItemcolumn[item_value] text
              , $FormExpressItemcolumn[default_value] varchar(100)
              , $FormExpressItemcolumn[cols] int(11)
              , $FormExpressItemcolumn[rows] int(11)
              , $FormExpressItemcolumn[max_length] int(11)
              , $FormExpressItemcolumn[multiple] tinyint(4)
              , $FormExpressItemcolumn[item_attributes] varchar(250)
              , $FormExpressItemcolumn[validation_rule] varchar(250)
              , $FormExpressItemcolumn[active] tinyint(4) NOT NULL default 0
              , $FormExpressItemcolumn[relative_position] varchar(10) NOT NULL default 'below'
              , PRIMARY KEY(fx_form_item_id)
              )";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED . $sql);
        return false;
    }

    //Create the cache table
    $FormExpressCachetable = $pntable['FormExpressCache'];
    $FormExpressCachecolumn = &$pntable['FormExpressCache_column'];

    // Create the table - the formatting here is not mandatory, but it does
    // make the SQL statement relatively easy to read.  Also, separating out
    // the SQL statement from the Execute() command allows for simpler
    // debug operation if it is ever needed
    $sql = "CREATE TABLE $FormExpressCachetable
              ( $FormExpressCachecolumn[form_id] int(11) NOT NULL
              , $FormExpressCachecolumn[form_data] blob default null
              , PRIMARY KEY(fx_form_id)
              )";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED . $sql);
        return false;
    }



    // Set up an initial value for a module variable.  Note that all module
    // variables should be initialised with some value in this way rather
    // than just left blank, this helps the user-side code and means that
    // there doesn't need to be a check to see if the variable is set in
    // the rest of the code as it always will be
    pnModSetVar('FormExpress', 'default_form_id', -1);
    pnModSetVar('FormExpress', 'allow_dynamic_syntax', 1);
    //pnModSetVar('FormExpress', 'itemsperpage', 10);

/* Can't load the module since it isn't active yet.
    if (!pnModLoad('FormExpress', 'admin')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return true; 
    }

    //Load the sample form
    $ModName = basename( dirname( __FILE__ ) );
    FormExpress_loadform(FormExpress_serialize2form("modules/$ModName/example.fxm"));
    FormExpress_loadform(FormExpress_serialize2form("modules/$ModName/example2.fxm"));
*/

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
            list($dbconn) = pnDBGetConn();
            $pntable = pnDBGetTables();
            $FormExpresstable = $pntable['FormExpress'];
            $FormExpresscolumn = &$pntable['FormExpress_column'];
            $FormExpressItemtable = $pntable['FormExpressItem'];
            $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];
            $sql = "ALTER TABLE $FormExpresstable
                          ADD fx_submit_action TEXT AFTER fx_description
                        , ADD fx_success_action TEXT AFTER fx_submit_action
                        , ADD fx_failure_action TEXT AFTER fx_success_action
                        , ADD fx_onload_action TEXT AFTER fx_failure_action
                        , ADD fx_validation_action TEXT AFTER fx_onload_action
                        ";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
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
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            // Drop non-required columns
            $sql = "ALTER TABLE $FormExpresstable DROP fx_action_source";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            $sql = "ALTER TABLE $FormExpresstable DROP fx_action_name";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            $sql = "ALTER TABLE $FormExpresstable DROP fx_action_args";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            $sql = "ALTER TABLE $FormExpresstable DROP fx_success_message";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            $sql = "ALTER TABLE $FormExpresstable DROP fx_failure_message";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }
            //Modify the items table
            $sql = "ALTER TABLE $FormExpressItemtable
                        MODIFY fx_prompt varchar(250)
                      , ADD fx_validation_rule varchar(250) AFTER fx_multiple
                      , CHANGE 'fx_class_name' 'fx_item_attributes' varchar(250)
                   ";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED . $sql);
                return false;
            }

            //Create the cache table
            $FormExpressCachetable = $pntable['FormExpressCache'];
            $FormExpressCachecolumn = &$pntable['FormExpressCache_column'];

            // Create the table - the formatting here is not mandatory, but it does
            // make the SQL statement relatively easy to read.  Also, separating out
            // the SQL statement from the Execute() command allows for simpler
            // debug operation if it is ever needed
            $sql = "CREATE TABLE $FormExpressCachetable
                      ( $FormExpressCachecolumn[form_id] int(11) NOT NULL
                      , $FormExpressCachecolumn[form_data] blob default null
                      , PRIMARY KEY(fx_form_id)
                      )";
            $dbconn->Execute($sql);

            // Check for an error with the database code, and if so set an
            // appropriate error message and return
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _CREATETABLEFAILED . $sql);
                return false;
            }


            pnModSetVar('FormExpress', 'default_form_id', 1);
            pnModSetVar('FormExpress', 'allow_dynamic_syntax', 1);
        break;
/*
        case 0.5:
            // Version 0.5 didn't have a 'number' field, it was added
            // in version 1.0

            // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
            // return arrays but we handle them differently.  For pnDBGetConn()
            // we currently just want the first item, which is the official
            // database handle.  For pnDBGetTables() we want to keep the entire
            // tables array together for easy reference later on
            // This code could be moved outside of the switch statement if
            // multiple upgrades need it
            list($dbconn) = pnDBGetConn();
            $pntable = pnDBGetTables();

            // It's good practice to name the table and column definitions you
            // are getting - $table and $column don't cut it in more complex
            // modules
            // This code could be moved outside of the switch statement if
            // multiple upgrades need it
            $FormExpresstable = $pntable['FormExpress'];
            $FormExpresscolumn = &$pntable['FormExpress_column'];

            // Add a column to the table - the formatting here is not
            // mandatory, but it does make the SQL statement relatively easy
            // to read.  Also, separating out the SQL statement from the
            // Execute() command allows for simpler debug operation if it is
            // ever needed
            $sql = "ALTER TABLE $FormExpresstable
                    ADD $FormExpresscolumn[number] int(5) NOT NULL default 0";
            $dbconn->Execute($sql);

            // Check for an error with the database code, and if so set an
            // appropriate error message and return
            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _UPDATETABLEFAILED);
                return false;
            }

            // At the end of the successful completion of this function we
            // recurse the upgrade to handle any other upgrades that need
            // to be done.  This allows us to upgrade from any version to
            // the current version with ease
            return FormExpress_upgrade(1.0);
        case 1.0:
            // Code to upgrade from version 1.0 goes here
            break;
        case 2.0:
            // Code to upgrade from version 2.0 goes here
            break;
*/
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
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn()
    // we currently just want the first item, which is the official
    // database handle.  For pnDBGetTables() we want to keep the entire
    // tables array together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // Drop the table - for such a simple command the advantages of separating
    // out the SQL statement from the Execute() command are minimal, but as
    // this has been done elsewhere it makes sense to stick to a single method
    $sql = "DROP TABLE $pntable[FormExpress]";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Drop the table - for such a simple command the advantages of separating
    // out the SQL statement from the Execute() command are minimal, but as
    // this has been done elsewhere it makes sense to stick to a single method
    $sql = "DROP TABLE $pntable[FormExpressItem]";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Drop the table - for such a simple command the advantages of separating
    // out the SQL statement from the Execute() command are minimal, but as
    // this has been done elsewhere it makes sense to stick to a single method
    $sql = "DROP TABLE $pntable[FormExpressCache]";
    $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        // Report failed deletion attempt
        return false;
    }

    // Delete any module variables
    pnModDelVar('FormExpress', 'default_form_id', 1);
    pnModDelVar('FormExpress', 'allow_dynamic_syntax', 1);

    // Deletion successful
    return true;
}

?>
