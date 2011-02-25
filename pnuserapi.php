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
 * Purpose of file:  FormExpress User API
 * ----------------------------------------------------------------------
 */

/**
 * get all Forms
 * @returns array
 * @return array of items, or false on failure
 */
function FormExpress_userapi_getall($args)
{
    // Security check for entire function
    if (!pnSecAuthAction(0, 'FormExpress::', '::', ACCESS_READ)) {
        return array();
    }

    // Security check for each item
    $permFilter = array('component_left'   => 'FormExpress',
                        'component_middle' => '',
                        'component_right'  => '',
                        'instance_left'    => 'form_name',
                        'instance_middle'  => '',
                        'instance_right'   => 'form_id',
                        'level'            => ACCESS_READ);

    $formObj = DBUtil::SelectObjectArray('FormExpress','','form_name',-1,-1,'',$permFilter);

    return $formObj;
}



/**
 * get a specific item
 * @param $args['form_id'] id of example item to get
 * @returns array
 * @return item array, or false on failure
 */
function FormExpress_userapi_get($args)
{
    // Check for passed form_id
    if ( $args['form_id'] ) {
        $form_id = $args['form_id'];
    } else {
        pnSessionSetVar('errormsg', __("Missing formid in items_getall"));
        return false;
    }

    $table = pnDBGetTables();
    // Security check for each item
    $permFilter = array('component_left'   => 'FormExpress',
                        'component_middle' => '',
                        'component_right'  => '',
                        'instance_left'    => 'form_name',
                        'instance_middle'  => '',
                        'instance_right'   => 'form_id',
                        'level'            => ACCESS_READ);

    $formObj = DBUtil::selectObjectById('FormExpress', $form_id, 'form_id', null, $permFilter);

    // Return the item array
    return $formObj;
}

/**
 * utility function to count the number of items held by this module
 * @returns integer
 * @return number of items held by this module
 */
/*  This appears to not be used, so I will comment it out for now.
function FormExpress_userapi_countitems()
{

    return DBUtil::selectObjectCount('FormExpress');
}
 *
 */

/**
 * get all example items
 * @returns array
 * @return array of items, or false on failure
 */
function FormExpress_userapi_items_getall($args)
{
    // Check for passed form_id
    if ( $args['form_id'] ) {
        $form_id = $args['form_id'];
    } else {
        pnSessionSetVar('errormsg', __("Missing formid in items_getall"));
        return false;
    }
    if (isset($args['status'])) {
        $status = $args['status'];
    }

    // Security check for FormExpress itself
    if (!pnSecAuthAction(0, 'FormExpress::', '::', ACCESS_READ)) {
        return $items;
    }

    $table = pnDBGetTables();
    $FormExpressItemcolumn = &$table['FormExpressItem_column'];

    // Retrieve all items with the passed form_id
    $where = 'WHERE ' . $FormExpressItemcolumn[form_id] . '=' . $form_id;

    // If a status was passed we add that as a WHERE clause
    if (isset($status)) {
        $where .= " AND $FormExpressItemcolumn[active] = ".(($status == 'inactive') ? 0 : 1 );
    }

    // Security check for each item
    $permFilter = array('component_left'   => 'FormExpress',
                        'component_middle' => '',
                        'component_right'  => '',
                        'instance_left'    => 'item_name',
                        'instance_middle'  => '',
                        'instance_right'   => $form_id,
                        'level'            => ACCESS_READ);
    $items = DBUtil::selectObjectArray(
            'FormExpressItem',
            $where,
            "ORDER BY $FormExpressItemcolumn[sequence]" , -1, -1, '',
            $permFilter
            );
    return $items;

}


function FormExpress_userapi_getvar($name='') {
  return 'Hello World (dynamic function)';
}
?>
