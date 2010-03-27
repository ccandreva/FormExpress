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
// Purpose of file:  Table information for FormExpress module
// ----------------------------------------------------------------------

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function FormExpress_pntables()
{
    // Initialise table array
    $pntable = array();

    // Get the name for the FormExpress item table.  This is not necessary
    // but helps in the following statements and keeps them readable
    $FormExpress = pnConfigGetVar('prefix') . '_FormExpress';

    // Set the table name
    $pntable['FormExpress'] = $FormExpress;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['FormExpress_column'] = array( 'form_id'    => $FormExpress . '.fx_form_id'
                                          , 'form_name'   => $FormExpress . '.fx_form_name'
                                          , 'description'   => $FormExpress . '.fx_description'
                                          , 'submit_action' => $FormExpress . '.fx_submit_action'
                                          , 'success_action' => $FormExpress . '.fx_success_action'
                                          , 'failure_action' => $FormExpress . '.fx_failure_action'
                                          , 'onload_action' => $FormExpress . '.fx_onload_action'
                                          , 'validation_action' => $FormExpress . '.fx_validation_action'
                                          //, 'action_source' => $FormExpress . '.fx_action_source'
                                          //, 'action_name' => $FormExpress . '.fx_action_name'
                                          //, 'action_args' => $FormExpress . '.fx_action_args'
                                          //, 'success_message' => $FormExpress . '.fx_success_message'
                                          //, 'failure_message' => $FormExpress . '.fx_failure_message'
                                          , 'active'   => $FormExpress . '.fx_active'
                                          , 'language'   => $FormExpress . '.fx_language'
                                          , 'input_name_suffix'   => $FormExpress . '.fx_input_name_suffix'
                                          );

    $FormExpressItem = pnConfigGetVar('prefix') . '_FormExpressItems';

    // Set the table name
    $pntable['FormExpressItem'] = $FormExpressItem;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['FormExpressItem_column'] = array( 'form_item_id'    => $FormExpressItem . '.fx_form_item_id'
                                               , 'form_id'   => $FormExpressItem . '.fx_form_id'
                                               , 'sequence'   => $FormExpressItem . '.fx_sequence'
                                               , 'item_type'   => $FormExpressItem . '.fx_item_type'
                                               , 'item_name'   => $FormExpressItem . '.fx_item_name'
                                               , 'required'   => $FormExpressItem . '.fx_required'
                                               , 'prompt'   => $FormExpressItem . '.fx_prompt'
                                               , 'prompt_position'   => $FormExpressItem . '.fx_prompt_position'
                                               , 'item_value'   => $FormExpressItem . '.fx_item_value'
                                               , 'default_value'   => $FormExpressItem . '.fx_default_value'
                                               , 'cols'   => $FormExpressItem . '.fx_cols'
                                               , 'rows'   => $FormExpressItem . '.fx_rows'
                                               , 'max_length'   => $FormExpressItem . '.fx_max_length'
                                               , 'item_attributes'   => $FormExpressItem . '.fx_item_attributes'
                                               , 'multiple'   => $FormExpressItem . '.fx_multiple'
                                               , 'validation_rule'   => $FormExpressItem . '.fx_validation_rule'
                                               , 'active'   => $FormExpressItem . '.fx_active'
                                               , 'relative_position'   => $FormExpressItem . '.fx_relative_position'
                                              );

    // Set the table name
    $FormExpressCache = pnConfigGetVar('prefix') . '_FormExpressCache';
    $pntable['FormExpressCache'] = $FormExpressCache;

    // Set the column names.  Note that the array has been formatted
    // on-screen to be very easy to read by a user.
    $pntable['FormExpressCache_column'] = array( 'form_id'   => $FormExpressCache . '.fx_form_id'
                                               , 'form_data'   => $FormExpressCache . '.fx_form_data'
                                               );



    // Return the table information
    return $pntable;
}
/*
For cut & pasting...
form_item_id
form_id
sequence
item_type
item_name
required
prompt
prompt_position
item_value
default_value
cols
rows
max_length
item_attributes
multiple
group_title
relative_position
active


form_item_id form_item_id
form_id form_id
sequence sequence
item_type item_type
item_name item_name
required required
prompt prompt
prompt_position prompt_position
item_value item_value
default_value default_value
cols cols
rows rows
max_length max_length
item_attributes item_attributes
multiple multiple
group_title group_title
relative_position relative_position
active active
*/
?>
