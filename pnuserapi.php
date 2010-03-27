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
// Purpose of file:  FormExpress user API
// ----------------------------------------------------------------------

/**
 * get all example items
 * @returns array
 * @return array of items, or false on failure
 */
function FormExpress_userapi_getall($args)
{
    //
    extract($args);

    $items = array();

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'FormExpress::', '::', ACCESS_READ)) {
        return $items;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $FormExpresstable = $pntable['FormExpress'];
    $FormExpresscolumn = &$pntable['FormExpress_column'];
    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $FormExpresscolumn[form_id]
                 , $FormExpresscolumn[form_name]
                 , $FormExpresscolumn[description]
                 , $FormExpresscolumn[submit_action]
                 , $FormExpresscolumn[success_action]
                 , $FormExpresscolumn[failure_action]
                 , $FormExpresscolumn[onload_action]
                 , $FormExpresscolumn[validation_action]
                 , $FormExpresscolumn[active]
                 , $FormExpresscolumn[language]
            FROM $FormExpresstable
            ORDER BY $FormExpresscolumn[form_name]";
    $result = $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
        list( $form_id
            , $form_name
            , $description
            , $submit_action
            , $success_action
            , $failure_action
            , $onload_action
            , $validation_action
            , $active
            , $language
            ) = $result->fields;
        if (pnSecAuthAction(0, 'FormExpress::', "$form_name::$form_id", ACCESS_READ)) {
            $items[] = array( 'form_id' => $form_id
                            , 'form_name' => $form_name
                            , 'description' => $description
                            , 'submit_action' => $submit_action
                            , 'success_action' => $success_action
                            , 'failure_action' => $failure_action
                            , 'onload_action' => $onload_action
                            , 'validation_action' => $validation_action
                            , 'active' => $active
                            , 'language' => $language
                            );
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();
    // Return the items
    return $items;
}



/**
 * get a specific item
 * @param $args['tid'] id of example item to get
 * @returns array
 * @return item array, or false on failure
 */
function FormExpress_userapi_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($form_id)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $FormExpresstable = $pntable['FormExpress'];
    $FormExpresscolumn = &$pntable['FormExpress_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT $FormExpresscolumn[form_id]
                 , $FormExpresscolumn[form_name]
                 , $FormExpresscolumn[description]
                 , $FormExpresscolumn[submit_action]
                 , $FormExpresscolumn[success_action]
                 , $FormExpresscolumn[failure_action]
                 , $FormExpresscolumn[onload_action]
                 , $FormExpresscolumn[validation_action]
                 , $FormExpresscolumn[active]
                 , $FormExpresscolumn[language]
                 , $FormExpresscolumn[input_name_suffix]
            FROM $FormExpresstable
            WHERE $FormExpresscolumn[form_id] = " . pnVarPrepForStore($form_id);
    $result = $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Check for no rows found, and if so return
    if ($result->EOF) {
        return false;
    }

    // Obtain the item information from the result set
    list( $form_id
        , $form_name
        , $description
        , $submit_action
        , $success_action
        , $failure_action
        , $onload_action
        , $validation_action
        , $active
        , $language
        , $input_name_suffix
        ) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'FormExpress::', "$form_name::$form_id", ACCESS_READ)) {
        return false;
    }

    // Create the item array
    $item = array('form_id' => $form_id
                 , 'form_name' => $form_name
                 , 'description' => $description
                 , 'submit_action' => $submit_action
                 , 'success_action' => $success_action
                 , 'failure_action' => $failure_action
                 , 'onload_action' => $onload_action
                 , 'validation_action' => $validation_action
                 , 'active' => $active
                 , 'language' => $language
                 , 'input_name_suffix' => $input_name_suffix
                 );

    // Return the item array
    return $item;
}

/**
 * utility function to count the number of items held by this module
 * @returns integer
 * @return number of items held by this module
 */
function FormExpress_userapi_countitems()
{
    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $FormExpresstable = $pntable['FormExpress'];
    $FormExpresscolumn = &$pntable['FormExpress_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
    $sql = "SELECT COUNT(1)
            FROM $FormExpresstable";
    $result = $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    // Obtain the number of items
    list($numitems) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Return the number of items
    return $numitems;
}

/**
 * get all example items
 * @returns array
 * @return array of items, or false on failure
 */
function FormExpress_userapi_items_getall($args)
{
    //
    extract($args);
    if ( (!isset($form_id) )
       ) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $items = array();

    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if (!pnSecAuthAction(0, 'FormExpress::', '::', ACCESS_READ)) {
        return $items;
    }

    // Get datbase setup - note that both pnDBGetConn() and pnDBGetTables()
    // return arrays but we handle them differently.  For pnDBGetConn() we
    // currently just want the first item, which is the official database
    // handle.  For pnDBGetTables() we want to keep the entire tables array
    // together for easy reference later on
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();

    // It's good practice to name the table and column definitions you are
    // getting - $table and $column don't cut it in more complex modules
    $FormExpressItemtable = $pntable['FormExpressItem'];
    $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];

    $additional_where_clause = '';
    if (isset($status)) {
        $additional_where_clause = " AND $FormExpressItemcolumn[active] = ".(($status == 'inactive') ? 0 : 1 );
    }

    // Get items - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the SelectLimit() command allows for simpler debug
    // operation if it is ever needed
    $sql = "SELECT $FormExpressItemcolumn[form_item_id]
                 , $FormExpressItemcolumn[form_id]
                 , $FormExpressItemcolumn[sequence]
                 , $FormExpressItemcolumn[item_type]
                 , $FormExpressItemcolumn[item_name]
                 , $FormExpressItemcolumn[required]
                 , $FormExpressItemcolumn[prompt]
                 , $FormExpressItemcolumn[prompt_position]
                 , $FormExpressItemcolumn[item_value]
                 , $FormExpressItemcolumn[default_value]
                 , $FormExpressItemcolumn[cols]
                 , $FormExpressItemcolumn[rows]
                 , $FormExpressItemcolumn[max_length]
                 , $FormExpressItemcolumn[multiple]
                 , $FormExpressItemcolumn[item_attributes]
                 , $FormExpressItemcolumn[validation_rule]
                 , $FormExpressItemcolumn[active]
                 , $FormExpressItemcolumn[relative_position]
            FROM $FormExpressItemtable
           WHERE $FormExpressItemcolumn[form_id] = "
               . pnVarPrepForStore($form_id)
               . $additional_where_clause
               ."
            ORDER BY $FormExpressItemcolumn[sequence]";

    $result = $dbconn->Execute($sql);

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _GETFAILED);
        return false;
    }

    // Put items into result array.  Note that each item is checked
    // individually to ensure that the user is allowed access to it before it
    // is added to the results array
    for (; !$result->EOF; $result->MoveNext()) {
        list( $form_item_id
            , $form_id
            , $sequence
            , $item_type
            , $item_name
            , $required
            , $prompt
            , $prompt_position
            , $item_value
            , $default_value
            , $cols
            , $rows
            , $max_length
            , $multiple
            , $item_attributes
            , $validation_rule
            , $active
            , $relative_position
            ) = $result->fields;
        if (pnSecAuthAction(0, 'FormExpress::', "$item_name::$form_id", ACCESS_READ)) {
            $items[] = array( 'form_item_id' => $form_item_id
                            , 'form_id' => $form_id
                            , 'sequence' => $sequence
                            , 'item_type' => $item_type
                            , 'item_name' => $item_name
                            , 'required' => $required
                            , 'prompt' => $prompt
                            , 'prompt_position' => $prompt_position
                            , 'item_value' => $item_value
                            , 'default_value' => $default_value
                            , 'cols' => $cols
                            , 'rows' => $rows
                            , 'multiple' => $multiple
                            , 'max_length' => $max_length
                            , 'item_attributes' => $item_attributes
                            , 'validation_rule' => $validation_rule
                            , 'active' => $active
                            , 'relative_position' => $relative_position
                            );
        }
    }

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();
    // Return the items
    return $items;
}


function FormExpress_userapi_getvar($name='') {
  return 'Hello World (dynamic function)';
}
?>
