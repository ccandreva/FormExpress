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
 * Purpose of file:  Table information for FormExpress module
 * ----------------------------------------------------------------------
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function FormExpress_pntables()
{
    // Initialise table array
    $table = array();

    $table['FormExpress'] = DBUtil::getLimitedTablename('FormExpress');
    $table['FormExpress_column'] = array(
        'form_id'    => 'fx_form_id',
        'form_name'   => 'fx_form_name',
        'description'   => 'fx_description',
        'submit_action' => 'fx_submit_action',
        'success_action' => 'fx_success_action',
        'failure_action' => 'fx_failure_action',
        'onload_action' => 'fx_onload_action',
        'validation_action' => 'fx_validation_action',
        'active'   => 'fx_active',
        'language'   => 'fx_language',
        'input_name_suffix'   => 'fx_input_name_suffix',
        );
    $table['FormExpress_column_def'] = array(
        'form_id'    => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'form_name'   => 'C(50) NOTNULL',
        'description'   => 'X2',
        'submit_action' => 'X2',
        'success_action' => 'X2',
        'failure_action' => 'X2',
        'onload_action' => 'X2',
        'validation_action' => 'X2',
        'active'   => 'L NOTNULL',
        'language'   => 'C(30) NOTNULL',
        'input_name_suffix'   => 'C(30) NOTNULL',
        );


    $table['FormExpressItem'] = DBUtil::getLimitedTablename('FormExpressItems');
    $table['FormExpressItem_column'] = array(
        'form_item_id'    => 'fx_form_item_id',
        'form_id'   => 'fx_form_id',
        'sequence'   => 'fx_sequence',
        'item_type'   => 'fx_item_type',
        'item_name'   => 'fx_item_name',
        'required'   => 'fx_required',
        'prompt'   => 'fx_prompt',
        'prompt_position'   => 'fx_prompt_position',
        'item_value'   => 'fx_item_value',
        'default_value'   => 'fx_default_value',
        'cols'   => 'fx_cols',
        'rows'   => 'fx_rows',
        'max_length'   => 'fx_max_length',
        'item_attributes'   => 'fx_item_attributes',
        'multiple'   => 'fx_multiple',
        'validation_rule'   => 'fx_validation_rule',
        'active'   => 'fx_active',
        'relative_position'   => 'fx_relative_position',
        );
    $table['FormExpressItem_column_def'] = array(
        'form_item_id'    => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'form_id'   => 'I NOTNULL',
        'sequence'   => 'I NOTNULL',
        'item_type'   => 'C(15) NOTNULL',
        'item_name'   => 'C(30) NOTNULL',
        'required'   => 'L NOTNULL',
        'prompt'   => "C(100)",
        'prompt_position'   => "C(10) DEFAULT 'left'",
        'item_value'   => 'X2',
        'default_value'   => 'C(100)',
        'cols'   => 'I',
        'rows'   => 'I',
        'max_length'   => 'I',
        'item_attributes'   => 'C(250)',
        'multiple'   => 'L',
        'validation_rule'   => 'C(250)',
        'active'   => 'L NOTNULL',
        'relative_position'   => "C(10) NOTNULL DEFAULT 'below'",
        );

    $table['FormExpressCache'] = DBUtil::getLimitedTablename('FormExpressCache');
    $table['FormExpressCache_column'] = array(
        'form_id'   => 'fx_form_id',
        'form_data'   => 'fx_form_data'
        );
    $table['FormExpressCache_column_def'] = array(
        'id'   => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'data'   => 'B DEFAULT NULL'
        );



    // Return the table information
    return $table;
}

?>
