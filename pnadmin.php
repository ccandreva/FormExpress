<?php
/**
 * FormExpress : Build forms for Zikula through a web interface
 *
 * @copyright (c) 2002 Stutchbury Limited, 2010 Chris Candreva
 * @Version $Id:                                              $
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
 * Purpose of file:  FormExpress admin display functions
 * ----------------------------------------------------------------------
 */
 

include_once( "pnclass/FXSession.php" );
include_once( "pnclass/FXCache.php" );
include_once( "pnclass/FXHtml.php" );

Loader::requireOnce('includes/pnForm.php');
require_once('pnclass/modifyformhandler.php');
require_once('pnclass/modifyconfighandler.php');
require_once('pnclass/importexporthandler.php');


/**
 * the main administration function
 * Just redirect to view.
 */
function FormExpress_admin_main()
{
    return pnRedirect(pnModURL('FormExpress', 'admin', 'view'));

}


/**
 * add new form
 * This is a standard function that is called whenever an administrator
 * wishes to create a new module item
 */
function FormExpress_admin_new()
{

    // Security check - important to do this as early as possible
    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_modifyformHandler();
    $output = $render->pnFormExecute('formexpress_admin_modify.html', $formobj);
    return $output;    

}

/**
 * modify an item
 * This is a standard function that is called whenever an administrator
 * wishes to modify a current module item
 * @param 'tid' the id of the item to be modified
 */
function FormExpress_admin_modify($args)
{
    $form_id = FormUtil::getPassedValue('form_id');

    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_modifyformHandler();
    if ( isset($form_id) && !empty($form_id)) {
      $formobj->SetFormId($form_id);
    }
    
    $output = $render->pnFormExecute('formexpress_admin_modify.html', $formobj);
    return $output;    
}


/**
 * delete item
 * This is a standard function that is called whenever an administrator
 * wishes to delete a form.  Note that this function is
 * @param 'id' the id of the form to be deleted
 * @param 'confirmation' confirmation that this item can be deleted
 */
function FormExpress_admin_delete($args)
{
    $form_id = FormUtil::getPassedValue('form_id');
    $confirmation = FormUtil::getPassedValue('confirmation');

    // User functions of this type can be called by other modules.
    extract($args);

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    $form = pnModAPIFunc('FormExpress',
                         'user',
                         'get',
                         array('form_id' => $form_id));

    if ($form == false) {
        return LogUtil::registerError( __("Form does not exist.", 404) );
    }

    /* Security check - We check the specific instance of the form.
     */
    if (!SecurityUtil::checkPermission('FormExpress::Item', "$form[form_name]::$form_id", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation. 
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user

        $render = pnRender::getInstance('FormExpress');
        $render->assign('form', $form);
        return $render->fetch('formexpress_admin_delete.html');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
      $render = pnRender::getInstance('FormExpress');
      return $render->fetch('formexpress_user_submit_form_badkey.html');
    }

    if (pnModAPIFunc('FormExpress', 'admin', 'delete',
                     array('form_id' => $form_id))) {
        // Success
        pnSessionSetVar('statusmsg', _FORMEXPRESSDELETED);
    }

    return pnRedirect(pnModURL('FormExpress', 'admin', 'view'));
}

/**
 * view items
 */
function FormExpress_admin_view()
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke

    
    if (!SecurityUtil::checkPermission('FormExpress::', '::', ACCESS_EDIT)) {
      return LogUtil::registerPermissionError();
    }

    $render = pnRender::getInstance('FormExpress');


    // The user API function is called.  This takes the number of items
    // required and the first number in the list of all items, which we
    // obtained from the input and gets us the information on the appropriate
    // items.
    $items = pnModAPIFunc( 'FormExpress', 'user', 'getall' );

    $forms = array();
    if ($items) {
        foreach ($items as $item) {

            $form = array();

            if (SecurityUtil::checkPermission('FormExpress::', "$item[form_name]::$item[form_id]", ACCESS_READ)) {
    
                $form = $item;

                $options = array();

                if (pnSecAuthAction(0, 'FormExpress::', "$item[form_name]::$item[form_id]", ACCESS_EDIT)) {
                    $options[] = '<a href="' 
                        . pnModURL('FormExpress', 'admin', 'modify', 'admin', 'modify',  array('form_id' => $item['form_id'])) 
                        . '">'
                        . __('Edit') . '</a>';

                    // If you can't edit, you can't delete, so this check is nested 
                    if (pnSecAuthAction(0, 'FormExpress::', "$item[form_name]::$item[form_id]", ACCESS_DELETE)) {
                        $options[] = '<a href="' 
                            . pnModURL('FormExpress', 'admin', 'delete', array('form_id' => $item['form_id']))
                            . '">'
                            . __('Delete') . '</a>';
                    }
                    $options[] = '<a href="'
                        . pnModURL('FormExpress', 'admin', 'items_view', array('form_id' => $item['form_id']))
                        . '">'
                        . __('Items') . '</a>';

                }  // End Check for ACCESS_EDIT 

                $form['options'] = join(' | ', $options);
                $forms[] = $form;
            }
        }
    }

    $render->assign('forms', $forms);
    return $render->fetch('formexpress_admin_view.html');

}


/**
 * This is a standard function to modify the configuration parameters of the
 * module
 */
function FormExpress_admin_modifyconfig()
{
    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_modifyconfigHandler();
    $output = $render->pnFormExecute('formexpress_admin_modifyconfig.html', $formobj);
    return $output;    

}


/**
 * Import or export forms
 */
function FormExpress_admin_import_export()
{

    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_importexportHandler();
    return $render->pnFormExecute('formexpress_admin_import_export.html', $formobj);
}

/** ***************************************************************************
 *
 */
function FormExpress_admin_export($args) {

    $form_id = FormUtil::getPassedValue('export_form_id');

    if (!isset($form_id)) {
       pnSessionSetVar('errormsg', _MODARGSERR);
       return false;
    }

    // User functions of this type can be called by other modules.
    extract($args);

    // We don't do pnSecConfirmAuthKey() here to allow multiple exports
    // not a major issue, because no DB updates are done - Fix by Jason Earl
    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $fxCache = new FXCache(false);
    $form = $fxCache->getForm($form_id);

    $modinfo = pnModGetInfo(pnModGetIDFromName('FormExpress'));
    $form['FormExpress_version'] = $modinfo['version'];

    /* We use our own MIME type to make sure it is saved as a file */
    header("Content-Type: application/FormExpress-Export\n");

    /* File to save as, spaced replaced with '_' */
    header("Content-Disposition: attachment; filename="
          . str_replace(' ', '_', $form['form_name']).".fxm");

    echo serialize($form);
    return true;
}


/** *********************************************************************************
 * Split into seperate function so it can be called on install
 */
function FormExpress_serialize2form($import_file_name) {
    //Read the file
    //How strange - I have to assign the $import_file to another var to get this to work!
    $filename = $import_file_name;
    //Added 'b' to read binary safe on Windows - Thank to Jason Earl for the hint
    $fd = @fopen ($filename, 'rb');
    $contents = fread ($fd, filesize ($filename));
    @fclose ($fd);
    //Unlink of temp file appears to work automagically (at end of script execution?)
    //but just in case...
    @unlink($fd);

    $form = array();
    $form = unserialize($contents);

    return ( $form);
}

/** *********************************************************************************
 * Need to put some exception handling in here
 */
function FormExpress_loadform($form) {

    unset($form['form_id']);
    $form['form_id'] = pnModAPIFunc('FormExpress', 'admin', 'create', $form);

    if ($form_id != false) {
        // Success
        pnSessionSetVar('statusmsg', _FORMEXPRESSCREATED);
    }

    if ( is_array($form['items']) ) {
        foreach ( $form['items'] as $item ) {
            unset($item['form_item_id']);
            $item['form_id'] = $form['form_id'];

            $item['form_item_id'] = pnModAPIFunc('FormExpress'
                                                , 'admin'
                                                , 'item_create'
                                                , $item
                                                );
            if ($form_item_id != false) {
                // Success
                pnSessionSetVar('statusmsg', _FORMEXPRESSCREATED);
            }
        }
    }

}



function FormExpress_adminmenu()
{
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();

    // Display status message if any.  Note that in future this functionality
    // will probably be in the theme rather than in this menu, but this is the
    // best place to keep it for now
    $output->TextArray(pnGetStatusMsg());
    $output->Linebreak(2);

    // Start options menu
    $output->TableStart(_FORMEXPRESS);
    $output->SetOutputMode(_PNH_RETURNOUTPUT);

    // Menu options.  These options are all added in a single row, to add
    // multiple rows of options the code below would just be repeated
    $columns = array();
    $columns[] = $output->URL(pnModURL('FormExpress',
                                        'admin',
                                        'new'),
                              _NEWFORMEXPRESS); 
    $columns[] = $output->URL(pnModURL('FormExpress',
                                        'admin',
                                        'view'),
                              _VIEWFORMEXPRESS); 
    $columns[] = $output->URL(pnModURL('FormExpress',
                                        'admin',
                                        'import_export'),
                              _FORMEXPRESSIMPORTEXPORT); 
    $columns[] = $output->URL(pnModURL('FormExpress',
                                        'admin',
                                        'modifyconfig'),
                              _EDITFORMEXPRESSCONFIG); 
    $columns[] = $output->URL('http://www.stutchbury.net'
                             , _FORMEXPRESSDOCS); 
    $output->SetOutputMode(_PNH_KEEPOUTPUT);

    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddRow($columns);
    $output->SetInputMode(_PNH_PARSEINPUT);

    $output->TableEnd();

    // Return the output that has been generated by this function
    return $output->GetOutput();
}

/**
 * Show FormExpress fields
 */
function FormExpress_showfields($mode, $item = '')
{
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();

    // Start the table that holds the information to be input.  Note how each
    // item in the form is kept logically separate in the code; this helps to
    // see which part of the code is responsible for the display of each item,
    // and helps with future modifications
    $output->TableStart();

    // Form Name
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSNAME));
    $row[] = $output->FormText('form_name', $item['form_name'], 50, 50);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Description
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSDESCRIPTION));
    $row[] = $output->FormTextArea('description', $item['description'], 3, 41);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left', 'top');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Submit Action
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSSUBMITACTION));
    $row[] = $output->FormTextArea('submit_action', $item['submit_action'], 4, 50);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Success Action
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSSUCCESSACTION));
    $row[] = $output->FormTextArea('success_action', $item['success_action'], 4, 50);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left', 'top');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Failure Action
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSFAILUREACTION));
    $row[] = $output->FormTextArea('failure_action', $item['failure_action'], 4, 50);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left', 'top');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // OnLoad Action
    //$row = array();
    //$output->SetOutputMode(_PNH_RETURNOUTPUT);
    //$row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSONLOADACTION));
    //$row[] = $output->FormTextArea('onload_action', $item['onload_action'], 4, 50);
    //$output->SetOutputMode(_PNH_KEEPOUTPUT);
    //$output->SetInputMode(_PNH_VERBATIMINPUT);
    //$output->TableAddrow($row, 'left', 'top');
    //$output->SetInputMode(_PNH_PARSEINPUT);

    // Validation Action
    //$row = array();
    //$output->SetOutputMode(_PNH_RETURNOUTPUT);
    //$row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSVALIDATIONACTION));
    //$row[] = $output->FormTextArea('validation_action', $item['validation_action'], 4, 50);
    //$output->SetOutputMode(_PNH_KEEPOUTPUT);
    //$output->SetInputMode(_PNH_VERBATIMINPUT);
    //$output->TableAddrow($row, 'left', 'top');
    //$output->SetInputMode(_PNH_PARSEINPUT);

    // Language
    // language list
    $langlist = languagelist();
    $lang = array();
    $lang[] = array( 'id' => '', 'selected' => 0, 'name' => 'All' );
    foreach( $langlist as $l => $lan ) {
        if( $lan == $item['language'] ) {
            $lang[] = array( 'id' => $l, 'selected' => 1, 'name' => $lan );
        }
        else {
            $lang[] = array( 'id' => $l, 'selected' => 0, 'name' => $lan );
        }
    }

    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSLANGUAGE));
    $row[] = $output->FormSelectMultiple('language', $lang, 0, 1, $item['language'] );
    //$row[] = $output->FormText('language', pnVarPrepForDisplay($item['language']), 5, 20);
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Active
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSACTIVE));
    $row[] = $output->FormCheckbox('active', pnVarPrepForDisplay($item['active']) );
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);

    $output->TableEnd();

    // Return the output that has been generated by this function
    return $output->GetOutput();
}


//======================= FormExpress Items code ==========================================
/**
 * view items
 */
function FormExpress_admin_items_view($args)
{
    if (!SecurityUtil::checkPermission('FormExpresss::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
    $form_id = FormUtil::getPassedValue('form_id');

    extract($args);

    if (!isset($form_id)) {
        return LogUtil::registerError( __("No form specified."), 500) ;
    }

    $dom = ZLanguage::getModuleDomain('FormExpress');
    $render = pnRender::getInstance('FormExpress');

    //Get the form from the cache
    $fxCache = new FXCache();
    $form = $fxCache->getForm($form_id);

    /**
     *  We need to know the relative position of the 'next' item, which 
     *  is not available in the format originally developed for the table format.
     *  We add it here.
     * This code should be moved to the FXcache object
     */
    $max = count($form['items']) - 1;
    /* Set first item position to be 'below' */
    $form['items'][0]['relative_position'] = 'below';
    $form['items'][0]['isfirst'] = true;
    $form['items'][$max]['islast'] = true;
    for ($i=0; $i<$max; $i++) {
      $form['items'][$i]['next_position'] = $form['items'][$i+1]['relative_position'];
      $form['items'][$i]['Num'] = $i;
    }
    $render->assign_by_ref('form', $form);

//  Set this to show the edit links, used when called from admin
    $render->assign('ShowEditLinks', '1');        

    // Get all the inactive items
    $inactive = pnModAPIFunc( 'FormExpress', 'user', 'items_getall', 
                         array ( 'form_id' => $form_id, 'status' => 'inactive' )
                );
    $render->assign_by_ref('inactive', $inactive);

    // Set up select box for field to add
    $types = array(
        array('text' => __(Text, $dom), 'value' => text),
        array('text' => __(Password, $dom), 'value' => password),
        array('text' => __(Textarea, $dom), 'value' => textarea),
        array('text' => __(Checkbox, $dom), 'value' => checkbox),
        array('text' => __(Radio, $dom), 'value' => radio),
        array('text' => __(Selectlist, $dom), 'value' => selectlist),
        array('text' => __(Submit, $dom), 'value' => submit),
        array('text' => __(Reset, $dom), 'value' => reset),
        array('text' => __(Button, $dom), 'value' => button),
        array('text' => __(Boilerplate, $dom), 'value' => boilerplate),
        array('text' => __(Hidden, $dom), 'value' => hidden),
        array('text' => __(Groupstart, $dom), 'value' => groupstart),
        array('text' => __(Groupend, $dom), 'value' => groupend),
    );
    $render->assign_by_ref('item_typeItems', $types);

    //Get all the form items
    $items = pnModAPIFunc( 'FormExpress', 'user', 'items_getall',
                         array( 'form_id' => $form_id, 'status' => 'active' )
                         );

    //Set up select box of where to place the new field
    $required_sequenceItems = array();
    foreach ( $items as $item ) {
        $required_sequenceItems[] = array( 'value' => $item['sequence']
                           , 'text' => substr( $item['item_name']
                                               .' ('
                                               .( ( $item['prompt'] ) ? $item['prompt'] 
                                                                      : $item['item_value'] )
                                             , 0, 40
                                             ).')'
                           );
    }
    // End of form is directly in the template, since it is constant and selected.
    //$required_sequenceItems[] = array( 'value' => '', 'text' => __('End of form', $dom) );
    $render->assign_by_ref('required_sequenceItems', $required_sequenceItems);

    // Return the output that has been generated by this function
    return $render->fetch('formexpress_admin_items_view.html');

}

/**
 * add new item
 * This is a standard function that is called whenever an administrator
 * wishes to create a new module item
 */
function FormExpress_admin_item_new($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    //Create an array to hold current form data
    $form_item = array();

    $form_id = FormUtil::getPassedValue('form_id');
    $item_type = FormUtil::getPassedValue('item_type');
    $required_sequence = FormUtil::getPassedValue('required_sequence');
    
    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // Security check - important to do this as early as possible
    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    if ((!isset($form_id)) || (!isset($item_type))) {
        return LogUtil::registerError( __("You have passed invalid values to this function."), 500) ;
    }
    $form_item['item_type'] = $item_type;

    // Set default values
    $form_item['active'] = '1';
    $form_item['form_id'] = $form_id;


    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();

    // Add menu to output - it helps if all of the module pages have a standard
    // menu at their head to aid in navigation
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Text(FormExpress_adminmenu());
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Title - putting a title ad the head of each page reminds the user what
    // they are doing
    $output->Title(_ADDFORMEXPRESSITEM);

    // Start form - note the use of pnModURL() to create the recipient URL of
    // this form.  All URLs should be generated through pnModURL() to ensure
    // compatibility with future versions of PostNuke
    $output->FormStart(pnModURL('FormExpress', 'admin', 'item_create'));

    // Add an authorisation ID - this adds a hidden field in the form that
    // contains an authorisation ID.  The authorisation ID is very important in
    // preventing certain attacks on the website
    $output->FormHidden('authid', pnSecGenAuthKey());

    $output->FormHidden('form_id', $form_id);
    $output->FormHidden('item_type', $item_type);
    $output->FormHidden('required_sequence', $required_sequence);

    // Start the table that holds the information to be input.  Note how each
    // item in the form is kept logically separate in the code; this helps to
    // see which part of the code is responsible for the display of each item,
    // and helps with future modifications

    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Text(FormExpress_item_showfields('new', $form_item));
    $output->SetInputMode(_PNH_PARSEINPUT);

    // End form
    $output->Text(_FORMEXPRESSREQUIREDFIELD);
    $output->LineBreak(2);
    $output->FormSubmit(_FORMEXPRESSITEMADD);
    $output->FormEnd();

    $output->Linebreak(2);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    if ( defined('_FORMEXPRESSITEM'.strtoupper($form_item['item_type']).'HELP')) {
        $output->Text(constant('_FORMEXPRESSITEM'.strtoupper($form_item['item_type']).'HELP'));
    }
    $output->Text(_FORMEXPRESSITEMGENERICHELP);
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Return the output that has been generated by this function
    return $output->GetOutput();
}

/**
 * This is a standard function that is called with the results of the
 * form supplied by FormExpress_admin_new() to create a new item
 * @param 'item_name' the name of the item to be created
 * @param 'number' the number of the item to be created
 */
function FormExpress_admin_item_create($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    //Create an array to hold user data
    $form_item = array();

    //Populate the user data array
    list( $form_item['form_id']
        , $form_item['sequence']
        , $form_item['item_type']
        , $form_item['item_name']
        , $form_item['item_name_pick']
        , $form_item['required']
        , $form_item['prompt']
        , $form_item['prompt_position']
        , $form_item['item_value']
        , $form_item['default_value']
        , $form_item['cols']
        , $form_item['rows']
        , $form_item['max_length']
        , $form_item['item_attributes']
        , $form_item['validation_rule']
        , $form_item['multiple']
        , $form_item['relative_position']
        , $form_item['active']
        , $form_item['required_sequence']
        ) = pnVarCleanFromInput( 'form_id'
                               , 'sequence'
                               , 'item_type'
                               , 'item_name'
                               , 'item_name_pick'
                               , 'required'
                               , 'prompt'
                               , 'prompt_position'
                               , 'item_value'
                               , 'default_value'
                               , 'cols'
                               , 'rows'
                               , 'max_length'
                               , 'item_attributes'
                               , 'validation_rule'
                               , 'multiple'
                               , 'relative_position'
                               , 'active'
                               , 'required_sequence'
                               );
    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    //Actually, we need to populate $form_item from extract... !TODO
    //Use wddx_deserialize() ????
    //extract($args);

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('FormExpress', 'admin', 'view'));
        return true;
    }

    if (!FormExpress_validate_item_args($form_item)) {
        $output = new FXHtml();
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(pnModFunc('FormExpress'
                               , 'admin'
                               , 'item_new'
                               , array( 'form_id' => $form_item['form_id']
                                      , 'item_type' => $form_item['item_type']
                                      , 'form_item' => $form_item
                                      )
                               )
                     );
              return $output->GetOutput();
    }

    // Notable by its absence there is no security check here.  This is because
    // the security check is carried out within the API function and as such we
    // do not duplicate the work here

    // Load API.  All of the actual work for the creation of the new item is
    // done within the API, so we need to load that in before we can do
    // anything.  If the API fails to load an appropriate error message is
    // posted and the function returns
    if (!pnModAPILoad('FormExpress', 'admin')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return $output->GetOutput();
    }

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array
    $form_item['form_item_id'] = pnModAPIFunc('FormExpress'
                       , 'admin'
                       , 'item_create'
                       , $form_item
                       );

    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if ($form_item['form_item_id'] != false) {
        // Success

        //We just want to clear the cache for this form.
        $fxCache = new FXCache(false);
        $fxCache->delForm($form_item['form_id']);

        pnSessionSetVar('statusmsg', _FORMEXPRESSITEMCREATED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    //pnRedirect(pnModURL('FormExpress', 'admin', 'view'));
    //Eventually sent this to create items
    pnRedirect( pnModURL( 'FormExpress'
                        , 'admin'
                        , 'items_view'
                        , array( 'form_id' => $form_item['form_id'] )
                        )
              );

    // Return
    return true;
}

/**
 * modify an item
 * This is a standard function that is called whenever an administrator
 * wishes to modify a current module item
 * @param 'tid' the id of the item to be modified
 */
function FormExpress_admin_item_modify($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    //Create the form_item array
    $form_item = array();

    list($form_item_id
        , $objectid)= pnVarCleanFromInput('form_item_id'
                                         , 'objectid'
                                         );


    // Admin functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

    // At this stage we check to see if we have been passed $objectid, the
    // generic item identifier.  This could have been passed in by a hook or
    // through some other function calling this as part of a larger module, but
    // if it exists it overrides $tid
    //
    // Note that this module couuld just use $objectid everywhere to avoid all
    // of this munging of variables, but then the resultant code is less
    // descriptive, especially where multiple objects are being used.  The
    // decision of which of these ways to go is up to the module developer
    if (!empty($objectid)) {
        $form_item_id = $objectid;
    }

    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();

    // Load API.  Note that this is loading the user API, that is because the
    // user API contains the function to obtain item information which is the
    // first thing that we need to do.  If the API fails to load an appropriate
    // error message is posted and the function returns
    if (!pnModAPILoad('FormExpress', 'user')) {
        $output->Text(_LOADFAILED);
        return $output->GetOutput();
    }

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    // If the $form_item_array is not already populated, get the form item
    if ( empty($form_item['form_item_id'])) {
        //TODO! This should really be in the adminapi...
        //because we never call individual items from pnuser
        $form_item = pnModAPIFunc( 'FormExpress'
                                 , 'user'
                                 , 'item_get'
                                 , array('form_item_id' => $form_item_id)
                                 );
    
        if ($form_item == false) {
            $output->Text(_FORMEXPRESSNOSUCHITEM);
            return $output->GetOutput();
        }
    }

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  However,
    // in this case we had to wait until we could obtain the item name to
    // complete the instance information so this is the first chance we get to
    // do the check
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$item[item_name]::$form_id", ACCESS_EDIT)) {
        $output->Text(_FORMEXPRESSNOAUTH);
        return $output->GetOutput();
    }

    // Add menu to output - it helps if all of the module pages have a standard
    // menu at their head to aid in navigation
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Text(FormExpress_adminmenu());
    $output->SetInputMode(_PNH_PARSEINPUT);

    // Title - putting a title ad the head of each page reminds the user what
    // they are doing
    $output->Title(_EDITFORMEXPRESSITEM);

    // Start form - note the use of pnModURL() to create the recipient URL of
    // this form.  All URLs should be generated through pnModURL() to ensure
    // compatibility with future versions of PostNuke
    $output->FormStart(pnModURL('FormExpress', 'admin', 'item_update'));

    // Add an authorisation ID - this adds a hidden field in the form that
    // contains an authorisation ID.  The authorisation ID is very important in
    // preventing certain attacks on the website
    $output->FormHidden('authid', pnSecGenAuthKey());

    // Add a hidden variable for the item id.  This needs to be passed on to
    // the update function so that it knows which item for which item to carry
    // out the update
    $output->FormHidden('form_id', $form_item['form_id']);
    $output->FormHidden('form_item_id', $form_item['form_item_id']);
    $output->FormHidden('item_type', $form_item['item_type']);

    // Start the table that holds the information to be input.  Note how each
    // item in the form is kept logically separate in the code; this helps to
    // see which part of the code is responsible for the display of each item,
    // and helps with future modifications
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Text(FormExpress_item_showfields('modify', $form_item));
    $output->SetInputMode(_PNH_PARSEINPUT);

    // End form
    $output->Text(_FORMEXPRESSREQUIREDFIELD);
    $output->Linebreak(2);
    $output->FormSubmit(_FORMEXPRESSITEMUPDATE);
    $output->FormEnd();

    $output->Linebreak(2);
    if ( defined('_FORMEXPRESSITEM'.strtoupper($form_item['item_type']).'HELP')) {
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(constant('_FORMEXPRESSITEM'.strtoupper($form_item['item_type']).'HELP'));
        $output->SetInputMode(_PNH_PARSEINPUT);
    }

    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->text(FormExpress_footer());

    // Return the output that has been generated by this function
    return $output->GetOutput();
}

/**
 * This is a standard function that is called with the results of the
 * form supplied by FormExpress_admin_modify() to update a current item
 * @param 'tid' the id of the item to be updated
 * @param 'item_name' the name of the item to be updated
 * @param 'number' the number of the item to be updated
 */
function FormExpress_admin_item_update($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke

    //Create an array to hold the form item
    $form_item = array();

    //Populate the user data array
    list( $form_item['form_item_id']
        , $form_item['form_id']
        , $form_item['sequence']
        , $form_item['item_type']
        , $form_item['item_name']
        , $form_item['item_name_pick']
        , $form_item['required']
        , $form_item['prompt']
        , $form_item['prompt_position']
        , $form_item['item_value']
        , $form_item['default_value']
        , $form_item['cols']
        , $form_item['rows']
        , $form_item['max_length']
        , $form_item['item_attributes']
        , $form_item['validation_rule']
        , $form_item['multiple']
        , $form_item['relative_position']
        , $form_item['active']
        ) = pnVarCleanFromInput( 'form_item_id'
                               , 'form_id'
                               , 'sequence'
                               , 'item_type'
                               , 'item_name'
                               , 'item_name_pick'
                               , 'required'
                               , 'prompt'
                               , 'prompt_position'
                               , 'item_value'
                               , 'default_value'
                               , 'cols'
                               , 'rows'
                               , 'max_length'
                               , 'item_attributes'
                               , 'validation_rule'
                               , 'multiple'
                               , 'relative_position'
                               , 'active'
                               );

    // User functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().

    // Need to load $arg into form_item array? !TODO
    //Use wddx_deserialize() ????
    //extract($args);

    // At this stage we check to see if we have been passed $objectid, the
    // generic item identifier.  This could have been passed in by a hook or
    // through some other function calling this as part of a larger module, but
    // if it exists it overrides $tid
    //
    // Note that this module couuld just use $objectid everywhere to avoid all
    // of this munging of variables, but then the resultant code is less
    // descriptive, especially where multiple objects are being used.  The
    // decision of which of these ways to go is up to the module developer
    if (!empty($objectid)) {
        $form_item['form_item_id'] = $objectid;
    }

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('FormExpress', 'admin', 'view'));
        return true;
    }

    if (!FormExpress_validate_item_args($form_item)) {
        $output = new FXHtml();
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(pnModFunc('FormExpress'
                               , 'admin'
                               , 'item_modify'
                               , array( 'form_id' => $form_item['form_id']
                                      , 'item_type' => $form_item['item_type']
                                      , 'form_item' => $form_item
                                      )
                               )
                     );
              return $output->GetOutput();
    }

    //if (!FormExpress_validate_item_args($form_item)) { 
    //      $output = new FXHtml();
    //      $output->Text(_FORMEXPRESSVALARDSERROR);
    //      return $output->GetOutput();
    //    }
    // Notable by its absence there is no security check here.  This is because
    // the security check is carried out within the API function and as such we
    // do not duplicate the work here

    // Load API.  All of the actual work for the update of the new item is done
    // within the API, so we need to load that in before we can do anything.
    // If the API fails to load an appropriate error message is posted and the
    // function returns
    if (!pnModAPILoad('FormExpress', 'admin')) {
        pnSessionSetVar('errormsg', _LOADFAILED);
        return $output->GetOutput();
    }

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array.
    //
    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if(pnModAPIFunc( 'FormExpress'
                   , 'admin'
                   , 'item_update'
                   , $form_item
                   )
       ) {
        // Success

        //We just want to clear the cache for this form.
        $fxCache = new FXCache(false);
        $fxCache->delForm($form_item['form_id']);

        pnSessionSetVar('statusmsg', _FORMEXPRESSITEMUPDATED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    pnRedirect(pnModURL('FormExpress'
                       , 'admin'
                       , 'items_view'
                       , array( 'form_id' => $form_item['form_id'] )
                       )
              );

    // Return
    return true;
}

/**
 * delete item
 * This is a standard function that is called whenever an administrator
 * wishes to delete a current module item.  Note that this function is
 * the equivalent of both of the modify() and update() functions above as
 * it both creates a form and processes its output.  This is fine for
 * simpler functions, but for more complex operations such as creation and
 * modification it is generally easier to separate them into separate
 * functions.  There is no requirement in the PostNuke MDG to do one or the
 * other, so either or both can be used as seen appropriate by the module
 * developer
 * @param 'tid' the id of the item to be deleted
 * @param 'confirmation' confirmation that this item can be deleted
 */
function FormExpress_admin_item_delete($args)
{
    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    list($form_item_id,
         $objectid,
         $confirmation) = pnVarCleanFromInput('form_item_id',
                                              'objectid',
                                              'confirmation');


    // User functions of this type can be called by other modules.  If this
    // happens then the calling module will be able to pass in arguments to
    // this function through the $args parameter.  Hence we extract these
    // arguments *after* we have obtained any form-based input through
    // pnVarCleanFromInput().
    extract($args);

     // At this stage we check to see if we have been passed $objectid, the
     // generic item identifier.  This could have been passed in by a hook or
     // through some other function calling this as part of a larger module, but
     // if it exists it overrides $tid
     //
     // Note that this module couuld just use $objectid everywhere to avoid all
     // of this munging of variables, but then the resultant code is less
     // descriptive, especially where multiple objects are being used.  The
     // decision of which of these ways to go is up to the module developer
     if (!empty($objectid)) {
         $form_item_id = $objectid;
     }                     

    // Create output object - this object will store all of our output so
    // that we can return it easily when required
    $output = new FXHtml();

    // Load API.  Note that this is loading the user API, that is because the
    // user API contains the function to obtain item information which is the
    // first thing that we need to do.  If the API fails to load an appropriate
    // error message is posted and the function returns
    if (!pnModAPILoad('FormExpress', 'user')) {
        $output->Text(_LOADFAILED);
        return $output->GetOutput();
    }

    // The user API function is called.  This takes the item ID which we
    // obtained from the input and gets us the information on the appropriate
    // item.  If the item does not exist we post an appropriate message and
    // return
    $form_item = pnModAPIFunc('FormExpress',
                         'user',
                         'item_get',
                         array('form_item_id' => $form_item_id));

    if ($form_item == false) {
        $output->Text(_FORMEXPRESSNOSUCHITEM);
        return $output->GetOutput();
    }

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  However,
    // in this case we had to wait until we could obtain the item name to
    // complete the instance information so this is the first chance we get to
    // do the check
    if (!pnSecAuthAction(0, 'FormExpress::Item', "$form_item[item_name]::$form_id", ACCESS_DELETE)) {
        $output->Text(_FORMEXPRESSNOAUTH);
        return $output->GetOutput();
    }

    // Check for confirmation. 
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation
        // of this action from the user


        // Add menu to output - it helps if all of the module pages have a
        // standard menu at their head to aid in navigation
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->Text(FormExpress_adminmenu());
        $output->SetInputMode(_PNH_PARSEINPUT);

        // Title - putting a title ad the head of each page reminds the user
        // what they are doing
        $output->Title(_DELETEFORMEXPRESSITEM);

        // Add confirmation to output.  Note that this uses a FXHtml helper
        // function to produce the requested confirmation in a standard
        // fashion.  This not only cuts down on code within the module but
        // allows it to be altered in future without the module developer
        // having to worry about it
        $output->ConfirmAction(_CONFIRMFORMEXPRESSITEMDELETE,
                               pnModURL('FormExpress',
                                        'admin',
                                        'item_delete', array('form_item_id' => $form_item_id)),
                               _CANCELFORMEXPRESSDELETE,
                               pnModURL( 'FormExpress'
                                       , 'admin'
                                       , 'items_view'
                                       , array('form_id' => $form_item['form_id'])
                                       )
                              );

        // Return the output that has been generated by this function
        return $output->GetOutput();
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL( 'FormExpress'
                           , 'admin'
                           , 'items_view'
                           , array('form_id' => $form_item['form_id'])
                           )
                  );
        return true;
    }

    // Load API.  All of the actual work for the deletion of the item is done
    // within the API, so we need to load that in before before we can do
    // anything.  If the API fails to load an appropriate error message is
    // posted and the function returns
    if (!pnModAPILoad('FormExpress', 'admin')) {
        $output->Text(_LOADFAILED);
        return $output->GetOutput();
    }

    // The API function is called.  Note that the name of the API function and
    // the name of this function are identical, this helps a lot when
    // programming more complex modules.  The arguments to the function are
    // passed in as their own arguments array.
    //
    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required
    if (pnModAPIFunc('FormExpress',
                     'admin',
                     'item_delete',
                     array('form_item_id' => $form_item_id))) {
        // Success

        //We just want to clear the cache for this form.
        $fxCache = new FXCache(false);
        $fxCache->delForm($form_item['form_id']);

        pnSessionSetVar('statusmsg', _FORMEXPRESSITEMDELETED);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    pnRedirect( pnModURL( 'FormExpress'
                        , 'admin'
                        , 'items_view'
                        , array('form_id' => $form_item['form_id'])
                        )
              );
    
    // Return
    return true;
}


function FormExpress_validate_item_args(&$form_item) {
        $form_item['item_name'] = str_replace(' ', '_', $form_item['item_name']);

        if ( (!empty($form_item['item_name_pick'])) && ($form_item['item_name_pick'] != 'newradiogroup') ) {
            $form_item['item_name'] = $form_item['item_name_pick'];
        }

        if ($form_item['item_type'] == 'boilerplate') {
            $form_item['item_name'] = _FORMEXPRESSITEMTYPELOVBOILERPLATE;
        }
        if ($form_item['item_type'] == 'reset') {
            $form_item['item_name'] = _FORMEXPRESSITEMTYPELOVRESET;
        }
        if ($form_item['item_type'] == 'groupstart') {
            $form_item['item_name'] = _FORMEXPRESSITEMTYPELOVGROUPSTART;
        }
        if ($form_item['item_type'] == 'groupend') {
            $form_item['item_name'] = _FORMEXPRESSITEMTYPELOVGROUPEND;
        }

        // Check for required values
        foreach ( $form_item as $key => $field_value) {
            if ( ( FormExpress_is_field_required($form_item['item_type'], $key) === '1' ) 
               &&( empty($field_value) )
                 ) {
                pnSessionSetVar('errormsg', _FORMEXPRESSMISSINGVALUES);
                return false;
                //echo '>>>'.$form_item['item_type'].' | '.$key. ' | '.$field_value.'<<<';
            }
        }
        return true;
    }

/**
 * Show item options
 */
function FormExpress_admin_get_item_options($item, $item_weight_range) {
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();
//Start of options
            // Options for the item.  Note that each item has the appropriate
            // levels of authentication checked to ensure that it is suitable
            // for display

            $options = array();
            $output->SetInputMode(_PNH_VERBATIMINPUT);
            $output->SetOutputMode(_PNH_RETURNOUTPUT);
            //$output->SetOutputMode(_PNH_KEEPOUTPUT);
            if (pnSecAuthAction(0, 'FormExpress::', "$item[item_name]::$item[form_item_id]", ACCESS_EDIT)) {
                $options[] = $output->URL(pnModURL('FormExpress',
                                                   'admin',
                                                   'item_modify',
                                                   array('form_item_id' => $item['form_item_id'])),
                                          '<img src="' . _FORMEXPRESSEDITICON . '" alt="' . _EDIT . '" border="0"/>'
					  );
                if (pnSecAuthAction(0, 'FormExpress::', "$item[item_name]::$item[form_item_id]", ACCESS_DELETE)) {
                    $options[] = $output->URL(pnModURL('FormExpress',
                                                       'admin',
                                                       'item_delete',
                                                       array('form_item_id' => $item['form_item_id'])),
                                          '<img src="' . _FORMEXPRESSDELETEICON . '" alt="' . _DELETE . '" border="0" />'
					  );
                }
                if ( isset($item_weight_range) ) {
                    if ( $item['sequence'] > $item_weight_range['min'] ) {
                        $options[] = $output->URL( pnModURL( 'FormExpress'
                                                           , 'admin'
                                                           , 'shift_item_weight'
                                                           , array( 'form_id' => pnVarPrepForStore($item['form_id'])
                                                                  , 'form_item_id' => pnVarPrepForStore($item['form_item_id'])
                                                                  , 'action' => 'lighter'
                                                                  , 'authid' => pnSecGenAuthKey() 
                                                                  )
                                                           )
                                                 , '<img src="' . _FORMEXPRESSMOVEUPICON . '" alt="' . _FORMEXPRESSMOVEUP . '"  border="0"/>'
                                                 );
                    }
                    if ( $item['sequence'] < $item_weight_range['max'] ) {
                        $options[] = $output->URL( pnModURL( 'FormExpress'
                                                           , 'admin'
                                                           , 'shift_item_weight'
                                                           , array( 'form_id' => pnVarPrepForStore($item['form_id'])
                                                                  , 'form_item_id' => pnVarPrepForStore($item['form_item_id'])
                                                                  , 'action' => 'heavier'
                                                                  , 'authid' => pnSecGenAuthKey() 
                                                                  )
                                                           )
                                                 , '<img src="' . _FORMEXPRESSMOVEDOWNICON . '" alt="' . _FORMEXPRESSMOVEDOWN . '"  border="0"/>'
                                                 );
                    }
                }

            }

    $output->SetInputMode(_PNH_PARSEINPUT);
    // Return the output that has been generated by this function
      return ( join(' ', $options) );
      return $options;

}
/**
 * Show fields
 */
function FormExpress_item_showfields($mode, $item = '') {
    // Create output object - this object will store all of our output so that
    // we can return it easily when required
    $output = new FXHtml();
    $output->FormHidden('sequence', pnVarPrepForDisplay($item['sequence']));

    // Start the table that holds the information to be input.  Note how each
    // item in the form is kept logically separate in the code; this helps to
    // see which part of the code is responsible for the display of each item,
    // and helps with future modifications
    $output->TableStart();

    //item_type - always shown - cannot be updated
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMTYPE));
    $row[] = $output->Text(pnVarPrepForDisplay($item['item_type']));
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);
    //$output->Linebreak(2);

    //item_name
    if ( $item['item_type'] == 'radio' ) {
    $input_list = array();
    if (!pnModAPILoad('FormExpress', 'admin')) {
        $output->Text(_LOADFAILED);
        return $output->GetOutput();
    }
    $input_list = FormExpress_get_radio_input_list( $item['form_id'] );
    $input_list[] = array( 'id' => 'newradiogroup', 'selected' => 0, 'name' => '--New Radio Group--' );
    }
    if ( FormExpress_is_field_required($item['item_type'], 'item_name')) {
        $row = array();
        $output->SetOutputMode(_PNH_RETURNOUTPUT);
        $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMNAME)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'item_name') === '1') ? '*' : '')
                                                  );
        $row[] = (($item['item_type'] == 'radio' ) ? $output->FormSelectMultiple('item_name_pick', $input_list, 0, 1, $item['item_name']).' ' : '' )
               . $output->FormText('item_name', pnVarPrepForDisplay($item['item_name']), 30, 50);
        $output->SetOutputMode(_PNH_KEEPOUTPUT);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->TableAddrow($row, 'left');
        $output->SetInputMode(_PNH_PARSEINPUT);
        //$output->Linebreak(2);
    } else {
        $output->FormHidden('item_name', pnVarPrepForDisplay($item['item_name']));
    }

    //required
    if ( FormExpress_is_field_required($item['item_type'], 'required')) {
        $row = array();
        $output->SetOutputMode(_PNH_RETURNOUTPUT);
        $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMREQ));
        $row[] = $output->FormCheckbox('required', pnVarPrepForDisplay($item['required']) );
        $output->SetOutputMode(_PNH_KEEPOUTPUT);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->TableAddrow($row, 'left');
        $output->SetInputMode(_PNH_PARSEINPUT);
    }

    //prompt
    if ( FormExpress_is_field_required($item['item_type'], 'prompt')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMPROMPT)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'prompt') === '1') ? '*' : '')
                                                 );
       $row[] = $output->FormText('prompt', $item['prompt'], 30, 99);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //prompt_position
    $prompt_list = array();
    $prompt_list[] = array( 'id' => 'above', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVABOVE );
    $prompt_list[] = array( 'id' => 'below', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVBELOW );
    $prompt_list[] = array( 'id' => 'leftcol', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVLEFTCOL );
    $prompt_list[] = array( 'id' => 'left', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVLEFT );
    $prompt_list[] = array( 'id' => 'right', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVRIGHT );
    $prompt_list[] = array( 'id' => 'hidden', 'selected' => 0, 'name' => _FORMEXPRESSPROMPTLOVHIDDEN );

    if ( FormExpress_is_field_required($item['item_type'], 'prompt_position')) {
        $row = array();
        $output->SetOutputMode(_PNH_RETURNOUTPUT);
        $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMPROMPTPOS));
        $row[] = $output->FormSelectMultiple('prompt_position', $prompt_list, 0, 1, $item['prompt_position']);
        //$row[] = $output->FormText('prompt_position', pnVarPrepForDisplay($item['prompt_position']) );
        $output->SetOutputMode(_PNH_KEEPOUTPUT);
        $output->SetInputMode(_PNH_VERBATIMINPUT);
        $output->TableAddrow($row, 'left');
        $output->SetInputMode(_PNH_PARSEINPUT);
    }

    //item_value
    if ( FormExpress_is_field_required($item['item_type'], 'item_value')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMVALUE)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'item_value') === '1') ? '*' : '')
                                                 );
       //$row[] = $output->FormTextArea('item_value', pnVarPrepForDisplay($item['item_value']), 4, 50);
       $row[] = $output->FormTextArea('item_value', $item['item_value'], 4, 50);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //default_value
    if ( FormExpress_is_field_required($item['item_type'], 'default_value')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMDEFVAL)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'default_value') === '1') ? '*' : '')
                                                  );
       //$row[] = $output->FormText('default_value', pnVarPrepForDisplay($item['default_value']), 50, 50);
       $row[] = $output->FormText('default_value', $item['default_value'], 50, 250);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //cols
    if ( FormExpress_is_field_required($item['item_type'], 'cols')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMCOLS)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'cols') === '1') ? '*' : '')
                                                  );
       $row[] = $output->FormText('cols', pnVarPrepForDisplay($item['cols']), 5, 5);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //rows
    if ( FormExpress_is_field_required($item['item_type'], 'rows')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMROWS)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'rows') === '1') ? '*' : '')
                                                 );
       $row[] = $output->FormText('rows', pnVarPrepForDisplay($item['rows']), 5, 5);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //max_length
    if ( FormExpress_is_field_required($item['item_type'], 'max_length')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMMAXLEN)
                                                  .(( FormExpress_is_field_required($item['item_type'], 'max_length') === '1') ? '*' : '')
                                                 );
       $row[] = $output->FormText('max_length', pnVarPrepForDisplay($item['max_length']), 5, 5);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //multiple
    if ( FormExpress_is_field_required($item['item_type'], 'multiple')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMMULTIPLE));
       $row[] = $output->FormCheckBox('multiple', pnVarPrepForDisplay($item['multiple']));
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //validation_rule
    if ( FormExpress_is_field_required($item['item_type'], 'validation_rule')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMVALRULE));
       //$row[] = $output->FormText('validation_rule', pnVarPrepForDisplay($item['validation_rule']), 50, 250);
       $row[] = $output->FormText('validation_rule', $item['validation_rule'], 50, 250);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //item_attributes
    if ( FormExpress_is_field_required($item['item_type'], 'item_attributes')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMATTR));
       //$row[] = $output->FormText('item_attributes', pnVarPrepForDisplay($item['item_attributes']), 50, 50);
       $row[] = $output->FormText('item_attributes', $item['item_attributes'], 50, 250);
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //relative_position
    $pos_list = array();
    $pos_list[] = array( 'id' => 'below', 'selected' => 0, 'name' => _FORMEXPRESSITEMPOSLOVBELOW );
    $pos_list[] = array( 'id' => 'right', 'selected' => 0, 'name' => _FORMEXPRESSITEMPOSLOVRIGHT );
    $pos_list[] = array( 'id' => 'inline', 'selected' => 0, 'name' => _FORMEXPRESSITEMPOSLOVINLINE );

    if ( FormExpress_is_field_required($item['item_type'], 'relative_position')) {
       $row = array();
       $output->SetOutputMode(_PNH_RETURNOUTPUT);
       $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSITEMRELPOS));
       $row[] = $output->FormSelectMultiple('relative_position', $pos_list, 0, 1, $item['relative_position']);

       //$row[] = $output->FormText('relative_position', pnVarPrepForDisplay($item['relative_position']));
       $output->SetOutputMode(_PNH_KEEPOUTPUT);
       $output->SetInputMode(_PNH_VERBATIMINPUT);
       $output->TableAddrow($row, 'left');
       $output->SetInputMode(_PNH_PARSEINPUT);
       //$output->Linebreak(2);
    }

    //active - always shown
    $row = array();
    $output->SetOutputMode(_PNH_RETURNOUTPUT);
    $row[] = $output->Text(pnVarPrepForDisplay(_FORMEXPRESSACTIVE));
    $row[] = $output->FormCheckbox('active', pnVarPrepForDisplay($item['active']) );
    $output->SetOutputMode(_PNH_KEEPOUTPUT);
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->TableAddrow($row, 'left');
    $output->SetInputMode(_PNH_PARSEINPUT);
    //$output->Linebreak(2);



    $output->TableEnd();
    //$output->Linebreak(2);
    //if ( defined('_FORMEXPRESSITEM'.strtoupper($item['item_type']).'HELP')) {
        //$output->Text(constant('_FORMEXPRESSITEM'.strtoupper($item['item_type']).'HELP'));
    //}
    // Return the output that has been generated by this function
    return $output->GetOutput();


}

function FormExpress_admin_shift_item_weight($args) {
    list ( $form_id
         , $form_item_id
         , $action
         , $authid
         ) = pnVarCleanFromInput ( 'form_id'
                                 , 'form_item_id'
                                 , 'action'
                                 , 'authid'
                                 );

    extract($args);

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect( pnModURL( 'FormExpress'
                            , 'admin'
                            , 'items_view'
                            , array( 'form_id' => $form_id )
                            )
                  );
        return true;
    }

    $output = new FXHtml();
    if (!pnModAPILoad('FormExpress', 'admin')) {
        $output->Text(_LOADFAILED);
        return $output->GetOutput();
    }

    if (!FormExpress_adminapi_shift_item_weight( $form_id
                                               , $form_item_id
                                               , $action
                                               )
       ) {
        $output->Text(pnSessionGetVar('errormsg'));
        return $output->GetOutput();
    }

    $fxCache = new FXCache();
    $fxCache->delForm($form_id);

    pnRedirect( pnModURL( 'FormExpress'
                        , 'admin'
                        , 'items_view'
                        , array( 'form_id' => $form_id )
                        )
              );
    return true;
}
/**
 * Admin Footer
 */
function FormExpress_footer() {
    // Get module info
    $modinfo = pnModGetInfo(pnModGetIDFromName('FormExpress'));
    $output = new FXHtml();
    $output->SetInputMode(_PNH_VERBATIMINPUT);
    $output->Linebreak(2);
    $output->TableStart();
    $output->TableRowStart();
    $output->TableColStart('', 'center' );
    //$output->Text("FormExpress ".$modinfo['version'].'rc1');
    $output->Text("FormExpress 0.3.5");
    $output->Text( " by " );
    $output->URL( "http://www.stutchbury.net/", "Stutchbury" );
    $output->TableColEnd();
    $output->TableRowEnd();
    $output->TableEnd();
//    $output->SetInputMode(_PNH_PARSEINPUT);
    return $output->getOutput();
}

function FormExpress_is_field_required($item_type, $field_name) {
    //                     boil chkb rad  sell text pass txta subm rese hid
    //sequence             x    x    x    x    x    x    x    x    x    x
    //item_type            x    x    x    x    x    x    x    x    x    x
    //item_name                      x    x    x    x    x    x    x    x    x
    //required                  x    x    x    x    x    x               
    //prompt                    x    x    x    x    x    x    x    x
    //prompt_position           x    x    x    x    x    x    x    x
    //item_value           x    x    x    x                             x
    //default_value             x    x    x    x         x
    //cols                                   . x    x    x
    //rows                                               x
    //max_length                               x    x    x
    //item_attributes           x    x    x    x    x    x    x
    //validation_rule           x    x    x    x    x    x    x
    //multiple                            x
    //active               x    x    x    x    x    x    x    x    x    x
    //relative_position    x    x    x    x    x    x    x    x    x

    $item_type_display = array( 'boilerplate' => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'              => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '-1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'checkbox'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '-1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'radio'       => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'selectlist'  => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '-1'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '-1'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0' //post+pnCleanFromVar issue
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'text'        => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '-1'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '1'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '-1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'password'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '-1'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '1'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '-1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'textarea'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '-1'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '1'
                                                      , 'max_length'        => '-1'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '-1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'submit'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'reset'       => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'button'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '-1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'hidden'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '0'
                                                      , 'prompt_position'   => '0'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '0'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '0'
                                                      )
                              , 'groupstart'  => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '-1'
                                                      , 'prompt_position'   => '-1'
                                                      , 'item_value'        => '-1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '-1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'groupend'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '0'
                                                      , 'prompt_position'   => '0'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '0'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '-1'
                                                      , 'relative_position' => '0'
                                                      )
                              );
  return $item_type_display[$item_type][$field_name];
}
?>
