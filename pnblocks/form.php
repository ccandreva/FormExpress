<?php
// $Id:$
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2002 Stutchbury Limited
// http://www.stutchbury.net
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
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Philip Fletcher
// Purpose of file: Show Form in a block
// ----------------------------------------------------------------------

//ModuleName var is not available here, so hard-coded FormExpress...
include_once( "modules/FormExpress/pnclass/FXHtml.php" );

/**
 * initialise block
 */
function FormExpress_formblock_init()
{
    // Security
    pnSecAddSchema('FormExpress::', 'Block title::');
}

/**
 * get information on block
 */
function FormExpress_formblock_info()
{
    // Values
    return array('text_type' => 'FormExpress',
                 'module' => 'FormExpress',
                 'text_type_long' => 'Display a FormExpress in a block',
                 'allow_multiple' => true,
                 'form_content' => true,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * display block
 */
function FormExpress_formblock_display($blockinfo)
{
    // Security check
    if (!pnSecAuthAction(0,
                         'FormExpress::',
                         "$blockinfo[title]::",
                         ACCESS_READ)) {
        return;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Create output object
    $output = new FXHtml();

    // Load API.  All of the actual work for the creation of the new item is
    // done within the API, so we need to load that in before we can do
    // anything.  If the API fails to load an appropriate error message is
    // posted and the function returns
    if (!pnModLoad('FormExpress', 'user')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return $output->GetOutput();
    }

    // Check
    if (empty($vars['form_id'])) {
        $output->Text(_FORMEXPRESSNOBLOCKFORMID);
    } else {

        //TODO! This is a workaround for the session_write_close() in index.php...
        //
        // Start session
        if (!pnSessionSetup()) {
            die('Session setup failed');
        }

        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(pnModFunc( 'FormExpress'
                               , 'user'
                               , 'display_form'
                               , array( 'form_id' => $vars['form_id']
                                      , 'admin_mode' => false
				      )
                               )
                     );
        // Populate block info and pass to theme
    }
    $blockinfo['content'] = $output->GetOutput();
    return themesideblock($blockinfo);
}


/**
 * modify block settings
 */
function FormExpress_formblock_modify($blockinfo)
{
    // Create output object
    $output = new FXHtml();

    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['form_id'])) {
        $vars['form_id'] = pnModGetVar('FormExpress', 'default_form_id');
    }


        // Get the form list
    // Load API.  All of the actual work for the creation of the new item is
    // done within the API, so we need to load that in before we can do
    // anything.  If the API fails to load an appropriate error message is
    // posted and the function returns
    if (!pnModAPILoad('FormExpress', 'user')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return $output->GetOutput();
    }

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array
    $forms = array();
    $forms = pnModAPIFunc( 'FormExpress'
                         , 'user'
                         , 'getall'
                         );

    // Create row
    $row = array();
    $formlist = array();
    //$formlist[] = array( 'id' => -1, 'selected' => 0, 'name' => '' );
    foreach( $forms as $id => $form ) {
        $formlist[] = array( 'id' => $form['form_id'], 'selected' => ($vars['form_id'] == $form['form_id'] ? 1: 0 ), 'name' => $form['form_name'] );
    }
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(_FORMEXPRESSNAME);
    $row[] = $output->FormSelectMultiple('form_id', $formlist );
    //$row[] = $output->FormText('form_id',
    //                           pnVarPrepForDisplay($vars['numitems']),
    //                           5,
    //                           5);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);

    // Add row
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddRow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Return output
    return $output->GetOutput();
}

/**
 * update block settings
 */
function FormExpress_formblock_update($blockinfo)
{
    $vars['form_id'] = pnVarCleanFromInput('form_id');

    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}

?>
