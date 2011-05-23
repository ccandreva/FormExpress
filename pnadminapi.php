<?php
/**
 * FormExpress : Build forms for Zikula through a web interface
 *
 * @copyright (c) 2002 Stutchbury Limited, 2010 Chris Candreva
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
 * Purpose of file:  FormExpress administration API
 * ----------------------------------------------------------------------
 */


/**
 * create a new FormExpress item
 * @param $args['formObj'] Object array of form data
 * @returns int
 * @return FormExpress item ID on success, false on failure
 */
function FormExpress_adminapi_create($args)
{
    // Extract form data object
    $formObj = $args[formObj];
    $form_name = $formObj[form_name];
    // Security check - important to do this as early as possible
    if (!SecurityUtil::checkPermission ('FormExpress::Item', "::$form_name", ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    $result = DBUtil::insertObject($formObj, 'FormExpress', 'form_id');
    if ($formObj['form_id']) {
        // Get the ID of the form that we inserted.
        $form_id = $formObj['form_id'];

        // Let any hooks know that we have created a new form.  As this is a
        // create hook we're passing 'form_id' as the extra info, which is the
        // argument that all of the other functions use to reference this
        // item
        pnModCallHooks('item', 'create', $form_id, 'form_id');

        // Return the id of the newly created item to the calling process
        return $form_id;
    } else {
        return $result;
    }
}

/**
 * delete an FormExpress
 * @param $args['form_id'] ID of the item
 * @returns bool
 * @return true on success, false on failure
 */
function FormExpress_adminapi_delete($args)
{

    // Make sure we received a form _id
    if (isset($args['form_id'])) {
        $form_id = $args['form_id'];
    } else {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Load the form, we need the name for the permissoins check
    $item = pnModAPIFunc('FormExpress', 'user', 'get',
            array('form_id' => $form_id)
            );

    if ($item == false) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOSUCHITEM);
        return false;
    }


    // Security check for delete access on this form
    if (!SecurityUtil::checkPermission('FormExpresss::', "$item[form_name]::$form_id", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    DBUtil::deleteObjectById ('FormExpressItem', $form_id, 'form_id');
    DBUtil::deleteObjectById ('FormExpress', $form_id, 'form_id');

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $form_id, '');

    // Let the calling process know that we have finished successfully
    return true;
}

/**
 * update a FormExpress Form
 * @param $args['form_id'] the ID of the item
 * @param $args['form_name'] the new name of the item
 * @param $args['number'] the new number of the item
 */
function FormExpress_adminapi_update($args)
{
    // Extract form data object
    $formObj = $args[formObj];
    $form_name = $formObj[form_name];
    $form_id = $formObj[form_id];
    // Security check - important to do this as early as possible
    if (!SecurityUtil::checkPermission ('FormExpress::Item', "::$form_name", ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }
    if( empty($formObj[active])) { $formObj[active] = 0; }

    // Load old data, we will need the current name for the security check
    $item = pnModAPIFunc('FormExpress', 'user', 'get',
            array('form_id' => $form_id));

    if ($item == false) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOSUCHITEM .'('.$form_id.')');
        return false;
    }

    // Security check against form name
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$item[form_name]::$form_id", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOAUTH);
        return false;
    }
    // If the name has changed, check against the new name too
    if ( $item[form_name] != $form_name) {
        if (!pnSecAuthAction(0, 'FormExpress::Item', "$form_name::$form_id", ACCESS_EDIT)) {
            pnSessionSetVar('errormsg', _FORMEXPRESSNOAUTH);
            return false;
        }
    }

    $stat = DBUtil::updateObject($formObj, 'FormExpress', '', 'form_id');
    return $stat;
}

//***************** ITEMS **********************
/**
 * create a new FormExpress item
 * @param $args['itemObj'] The item to add
 * @returns int
 * @return FormExpress item ID on success, false on failure
 */
function FormExpress_adminapi_item_create($args)
{
    // extract($args);
    $itemObj = $args[itemObj];
    $form_id = $itemObj['form_id'];
    $form_item_id = $itemObj['form_item_id'];
    $required_sequence = $itemObj['required_sequence'];
    $item_name = $itemObj['item_name'];
    
    // See if we have permission to add an item
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission ('FormExpress::Item', "$item_name::", ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    $table = pnDBGetTables();
    $FormExpressItemtable = $table['FormExpressItem'];
    $FormExpressItemcolumn = &$table['FormExpressItem_column'];
    
    //Get the weight
    $new_sequence = FormExpress_adminapi_new_weight
                        ( array ('table' => $FormExpressItemtable,
                                'weight_column' => $FormExpressItemcolumn['sequence'],
                                'additional_where_clause' => $FormExpressItemcolumn['form_id'].' = '. pnVarPrepForStore($form_id)
                                )
                        );
    $itemObj['sequence'] = $new_sequence;
    
    unset($itemObj['form_item_id']);
    $result = DBUtil::insertObject($itemObj, 'FormExpressItem', 'form_item_id');
    if (!$result) {
        pnSessionSetVar('errormsg', _CREATEFAILED );
        return false;
    }

    // Get the ID of the item that we inserted.
    $form_item_id = $itemObj['form_item_id'];

    //This is where we move the item to it's required position
    //Loop through the weight shifter 'til we're in the right place
    
//$required_sequence = 100;

    if ( $required_sequence ) {
        //Safety net in case required seq is too low
        $shift_weight_range = FormExpress_adminapi_get_item_weight_range( $form_id );
        if ( $required_sequence < $shift_weight_range['min'] ) {
            $required_sequence = $shift_weight_range['min'];
        }
        while ( $new_sequence > $required_sequence ) {
            $new_sequence = FormExpress_adminapi_shift_item_weight(
                    array('form_id' => $form_id, 'form_item_id' => $form_item_id,'action' => 'lighter')
            );
        }
    }

    // Let any hooks know that we have created a new item.  As this is a
    pnModCallHooks('item', 'create', $form_item_id, 'form_item_id');

    // Return the id of the newly created item to the calling process
    return $form_item_id;
}


/**
 * update a FormExpress item
 * @param $args['tid'] the ID of the item
 * @param $args['item_name'] the new name of the item
 * @param $args['number'] the new number of the item
 */
function FormExpress_adminapi_item_update($args)
{
    // Get arguments from argument array - all arguments to
    $itemObj = $args['itemObj'];
    $form_item_id = $itemObj['form_item_id'];

    // See if we have permission to add an item
    // avoid potential security holes or just too much wasted processing
    if (!SecurityUtil::checkPermission ('FormExpress::Item', "$item_name::", ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }


    if( empty($itemObj[active])) { $itemObj[active] = 0; }

    // The user API function is called.  This takes the item ID which
    // we obtained from the input and gets us the information on the
    // appropriate item.  If the item does not exist we post an appropriate
    // message and return
    $item = pnModAPIFunc('FormExpress', 'admin', 'item_get',
            array('form_item_id' => $form_item_id));

    if ($item == false) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOSUCHITEM .'('.$form_item_id.')');
        return false;
    }

    // Security check - important to do this as early on as possible to 
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$item[item_name]::$form_id", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOAUTH);
        return false;
    }
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$item_name::$form_id", ACCESS_EDIT)) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOAUTH);
        return false;
    }

    $stat = DBUtil::updateObject($itemObj, 'FormExpressItem', '', 'form_item_id');
    return $stat;

}

/**
 * delete an FormExpress
 * @param $args['tid'] ID of the item
 * @returns bool
 * @return true on success, false on failure
 */
function FormExpress_adminapi_item_delete($args)
{
    extract($args);

    // Argument check - make sure that all required arguments are present,
    // if not then set an appropriate error message and return
    if (!isset($form_item_id)) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    // Load form for security check against name
    $item = pnModAPIFunc('FormExpress', 'admin', 'item_get',
            array('form_item_id' => $form_item_id));

    if ($item == false) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOSUCHITEM);
        return false;
    }

    // Security check - important to do this as early on as possible to 
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$item[item_name]::$form_id", ACCESS_DELETE)) {
        pnSessionSetVar('errormsg', _FORMEXPRESSNOAUTH);
        return false;
    }

    // Delete item via DBUtil
    DBUtil::deleteObjectById ('FormExpressItem', $form_item_id, 'form_item_id');

    // Let any hooks know that we have deleted an item.  As this is a
    // delete hook we're not passing any extra info
    pnModCallHooks('item', 'delete', $form_item_id, '');

    // Let the calling process know that we have finished successfully
    return true;
}



/* Get list of all radio button groups for given form */
function FormExpress_adminapi_get_radio_input_list( $args )
{
  $form_id = $args['id'];

    if ( (empty($form_id))
       ) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    $table = pnDBGetTables();
    $FormExpressItemtable = $table['FormExpressItem'];
    $FormExpressItemcolumn = &$table['FormExpressItem_column'];

    $where = "WHERE $FormExpressItemcolumn[form_id] = " . pnVarPrepForStore($form_id)
          ." AND $FormExpressItemcolumn[item_type] = 'radio' "
          ;
    $name = DBUtil::SelectFieldArray('FormExpressItem', 'item_name', $where, '', 1);
    $input_list = array();

    foreach ($name as $input_name) {
        $input_list[] = array( 'id' => $input_name
                             , 'selected' => 0
                             , 'name' => $input_name
                             , 'text' => $input_name
                             , 'value' => $input_name
                             );
    }
    return $input_list;
}

function FormExpress_adminapi_new_weight($args) {
    extract($args);
    if ( (!isset($table))) {
        pnSessionSetVar('errormsg', 'Missing table in new_weght');
        return false;
    }
    if ( (!isset($weight_column))  ) {
        pnSessionSetVar('errormsg', 'Missing weight_column in new_weight');
        return false;
    }

    $sql = 'SELECT max('.$weight_column.') as wm'
          .'  FROM '.$table
          .' WHERE '.$additional_where_clause
          ;

    $result = DBUtil::executeSQL($sql);
    list($row) = DBUtil::marshallObjects($result, array('wm'));
    $weight = $row['wm'] + 10;
    return $weight;

}
/**
 * FormExpress (items) specific wrapper 
 */
function FormExpress_adminapi_get_item_weight_range( $form_id
                                                   ) {
    $pntable = pnDBGetTables();
    $FormExpressItemtable = $pntable['FormExpressItem'];
    $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];

    return ( FormExpress_get_weight_range
               ( $FormExpressItemtable
               , $FormExpressItemcolumn['sequence']
               , $FormExpressItemcolumn['form_id'].' = '. $form_id
                 .' AND '.$FormExpressItemcolumn['active'].' = 1'
               )
           );
}
/**
 * FormExpress (items) specific wrapper 
 */
function FormExpress_adminapi_shift_item_weight( $args ) {

    $form_id = $args['form_id'];
    $form_item_id = $args['form_item_id'];
    $action = $args['action'];

    $pntable = pnDBGetTables();
    $FormExpressItemtable = $pntable['FormExpressItem'];
    $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];

    return ( FormExpress_shift_weight
               ( $FormExpressItemtable
               , $FormExpressItemcolumn['form_item_id']
               , $FormExpressItemcolumn['sequence']
               , $form_item_id
               , $action
               , $FormExpressItemcolumn['form_id'].' = '. $form_id
               )
           );
}
/**
 * This is a generic utility function for getting the min or max weight
 * Create wrapper functions for each table you want to use this.
 */
function FormExpress_get_weight_range( $table
                                     , $weight_column
                                     , $additional_where_clause='1=1'
                                     ) {
    if ( (empty($table))
       ||(empty($weight_column))
       ) {
        pnSessionSetVar('errormsg', _MODARGSERROR);
        return false;
    }

    list($dbconn) = pnDBGetConn();
    //Don't need to get pntables - using $column
    $sql = 'SELECT min('.$weight_column.')'
          .'     , max('.$weight_column.')'
          .'  FROM '.$table
          .' WHERE '.$additional_where_clause
          ;
    $result = $dbconn->Execute($sql);
    list($min, $max) = $result->fields;
    return array( 'min' => $min
                , 'max' => $max
                );
}

/**
 * This is a generic utility function for moving items up or down a weighted order
 * Create wrapper functions for each table you want to use this.
 */
function FormExpress_shift_weight( $table
                                 , $pk_column
                                 , $weight_column
                                 , $pk_value
                                 , $action = 'heavier'
                                 , $additional_where_clause='1=1'
                                 ) {
    if ( (empty($table))
       ||(empty($pk_column))
       ||(empty($weight_column))
       ||(empty($pk_value))
       ) {
        pnSessionSetVar('errormsg', _MODARGSERROR.$table.$pk_column.$weight_column.$pk_value.'<<<');
        return false;
    }

    if (!isset($action)) { $action == 'heavier'; }
    if (!isset($additional_where_clause)) { $additional_where_clause == '1=1'; }

    list($dbconn) = pnDBGetConn();
    //Don't need to get pntables - using $pk_column etc..

    //If action = lighter then swap with prev row
    //Get the current row
    $sql = 'SELECT '.$pk_column
          .'     , '.$weight_column
          .'  FROM '.$table
          .' WHERE '.$pk_column.' = '.$pk_value
          .'   AND '.$additional_where_clause 
          ;
    $result = $dbconn->Execute($sql);
    list($curr_pk, $curr_weight) = $result->fields;
    //Get the next or prev row
    //We need the ORDER BY to ensure we fetch the next consecutive row, not just any row > curr_weight!
    $sql = 'SELECT '.$pk_column
          .'     , '.$weight_column
          .'  FROM '.$table
          .' WHERE '.$weight_column.(($action == 'lighter') ? ' < ' : ' > ').$curr_weight
          .'   AND '.$additional_where_clause 
          .' ORDER BY '.$weight_column
          .' '.(($action == 'lighter') ? DESC : ASC)
          ;
    $result2 = $dbconn->SelectLimit($sql, 1);
    list($swap_pk, $swap_weight) = $result2->fields;
    //Should always find the curr weight. but may not find the swap item
    if (!empty($swap_pk)) {
        $sql = 'UPDATE '.$table
              .'   SET '.$weight_column.' = '.$swap_weight
              .' WHERE '.$pk_column.' = '.$curr_pk
              ;
        $result = $dbconn->Execute($sql);
        $sql = 'UPDATE '.$table
              .'   SET '.$weight_column.' = '.$curr_weight
              .' WHERE '.$pk_column.' = '.$swap_pk
              ;
        $result = $dbconn->Execute($sql);
        return $swap_weight;
    }
    return $curr_weight;
    //return true;
}

/**
 * get a specific item
 * @param $args['tid'] id of example item to get
 * @returns array
 * @return item array, or false on failure
 */
function FormExpress_adminapi_item_get($args)
{
    // Get arguments from argument array - all arguments to this function
    // should be obtained from the $args array, getting them from other places
    // such as the environment is not allowed, as that makes assumptions that
    // will not hold in future versions of PostNuke
    extract($args);

    // Argument check - make sure that all required arguments are present, if
    // not then set an appropriate error message and return
    if (!isset($form_item_id)) {
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
    $FormExpressItemtable = $pntable['FormExpressItem'];
    $FormExpressItemcolumn = &$pntable['FormExpressItem_column'];

    // Get item - the formatting here is not mandatory, but it does make the
    // SQL statement relatively easy to read.  Also, separating out the sql
    // statement from the Execute() command allows for simpler debug operation
    // if it is ever needed
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
                 , $FormExpressItemcolumn[item_attributes]
                 , $FormExpressItemcolumn[validation_rule]
                 , $FormExpressItemcolumn[multiple]
                 , $FormExpressItemcolumn[active]
                 , $FormExpressItemcolumn[relative_position]
            FROM $FormExpressItemtable
           WHERE $FormExpressItemcolumn[form_item_id] = ". pnVarPrepForStore($form_item_id);
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
        , $item_attributes
        , $validation_rule
        , $multiple
        , $active
        , $relative_position
        ) = $result->fields;

    // All successful database queries produce a result set, and that result
    // set should be closed when it has been finished with
    $result->Close();

    // Security check - important to do this as early on as possible to avoid
    // potential security holes or just too much wasted processing.  Although
    // this one is a bit late in the function it is as early as we can do it as
    // this is the first time we have the relevant information
    if (!pnSecAuthAction(0, 'FormExpress::', "$item_name::$form_id", ACCESS_READ)) {
        return false;
    }

    // Create the item array
    $item = array( 'form_item_id'  => $form_item_id
                 , 'form_id'  => $form_id
                 , 'sequence'  => $sequence
                 , 'item_type'  => $item_type
                 , 'item_name'  => $item_name
                 , 'required'  => $required
                 , 'prompt'  => $prompt
                 , 'prompt_position'  => $prompt_position
                 , 'item_value'  => $item_value
                 , 'default_value'  => $default_value
                 , 'cols'  => $cols
                 , 'rows'  => $rows
                 , 'max_length'  => $max_length
                 , 'item_attributes'  => $item_attributes
                 , 'validation_rule'  => $validation_rule
                 , 'multiple'  => $multiple
                 , 'relative_position'  => $relative_position
                 , 'active'  => $active
                 );
    // Return the item array
    return $item;
}


/**
 * get available admin panel links
 *
 * @author Chris Candreva
 * @return array array of admin links
 */

function FormExpress_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('FormExpress');
    return  array (
        array ('url' => pnModURL('FormExpress', 'admin', 'new'), 'text' => __('New Form', $dom) ),
        array ('url' => pnModURL('FormExpress', 'admin', 'view'), 'text' => __('View Form', $dom) ),
        array ('url' => pnModURL('FormExpress', 'admin', 'import_export'), 'text' => __('Import/Export', $dom) ),
        array ('url' => pnModURL('FormExpress', 'admin', 'modifyconfig'), 'text' => __('Edit Form Configuration', $dom) ),
        array ('url' => pnModURL('FormExpress', 'admin', 'view_docs'), 'text' => __('Documentation', $dom) ),
    );
}

?>
