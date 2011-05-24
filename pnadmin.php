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
 * Purpose of file:  FormExpress admin display functions
 * ----------------------------------------------------------------------
 */
 

include_once( "pnclass/FXSession.php" );
include_once( "pnclass/FXCache.php" );

Loader::requireOnce('includes/pnForm.php');
require_once('pnclass/modifyformhandler.php');
require_once('pnclass/modifyconfighandler.php');
require_once('pnclass/importexporthandler.php');
require_once('pnclass/item_modifyhandler.php');


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
    return $render->pnFormExecute('formexpress_admin_modify.html', $formobj);

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
    
    return $render->pnFormExecute('formexpress_admin_modify.html', $formobj);
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
    $form = pnModAPIFunc('FormExpress', 'user', 'get',
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
        $render->assign_by_ref('form', $form);
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

        //We just want to clear the cache for this form.
        $fxCache = new FXCache(false);
        $fxCache->delForm($form_id);

        // Success
        $dom = ZLanguage::getModuleDomain('FormExpress');
        pnSessionSetVar('statusmsg', __('Form deleted.', $dom));
    }

    return pnRedirect(pnModURL('FormExpress', 'admin', 'view'));
}

/**
 * view items
 */
function FormExpress_admin_view()
{
    
    if (!SecurityUtil::checkPermission('FormExpress::', '::', ACCESS_EDIT)) {
      return LogUtil::registerPermissionError();
    }

    $render = pnRender::getInstance('FormExpress');

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
                        . pnModURL('FormExpress', 'admin', 'modify', array('form_id' => $item['form_id'])) 
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
    return $render->pnFormExecute('formexpress_admin_modifyconfig.html', $formobj);

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

    $form_id = FormUtil::getPassedValue('form_id');
    $item_type = FormUtil::getPassedValue('item_type');
    $required_sequence = FormUtil::getPassedValue('required_sequence');

    // Admin functions of this type can be called by other modules.
    extract($args);

    // Security check - important to do this as early as possible
    if (!SecurityUtil::checkPermission ('FormExpress::Item', '::', ACCESS_ADD)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_item_modifyHandler();

    if (isset($form_id) && (isset($item_type))) {

        //Create an array to hold current form data
        $form_item = array( 'item_type' => $item_type,
                        'active' => '1',
                        'form_id' => $form_id,
                        'required_sequence' => $required_sequence,
                        'func' => 'new',
                        );

        $formobj->setParams( $form_item );
    }

    return $render->pnFormExecute('formexpress_admin_item_modify.html', $formobj);
}


/**
 * modify an item
 * This is a standard function that is called whenever an administrator
 * wishes to modify a current module item
 * @param 'tid' the id of the item to be modified
 */
function FormExpress_admin_item_modify($args)
{

    // Secuity check, first thing since we don't check individual items.
    if (!SecurityUtil::checkPermission('FormExpress::', '::', ACCESS_EDIT)) {
      return LogUtil::registerPermissionError();
    }

    // Get parameters from whatever input we need.  All arguments to this
    // function should be obtained from pnVarCleanFromInput(), getting them
    // from other places such as the environment is not allowed, as that makes
    // assumptions that will not hold in future versions of PostNuke
    //Create the form_item array
//    $form_item = array();

    $render = FormUtil::newpnForm('FormExpress');
    $formobj = new formexpress_admin_item_modifyHandler();

    $form_id = FormUtil::getPassedValue('form_id');
    $form_item_id = FormUtil::getPassedValue('form_item_id');

    // Admin functions of this type can be called by other modules. 
    extract($args);

    if ($form_item_id) {
        $formobj->setParams( array('form_id' => $form_id, 'form_item_id' => $form_item_id, 'func' => 'modify') );
    }

    return $render->pnFormExecute('formexpress_admin_item_modify.html', $formobj);
}

/**
 * delete item
 * @param 'id' the id of the item to be deleted
 * @param 'confirmation' confirmation that this item can be deleted
 */
function FormExpress_admin_item_delete($args)
{
    $form_item_id = FormUtil::getPassedValue('form_item_id');
    $confirmation = FormUtil::getPassedValue('confirmation');

    // User functions of this type can be called by other modules.
    extract($args);

    // We need the item name to check permissions
    $form_item = pnModAPIFunc('FormExpress', 'admin', 'item_get',
                         array('form_item_id' => $form_item_id));

    if ($form_item == false) {
        return LogUtil::registerError( __("Selected item does not exist.", 404) );
    }

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  However,
    // in this case we had to wait until we could obtain the item name to
    // complete the instance information so this is the first chance we get to
    // do the check
    if (!SecurityUtil::checkPermission('FormExpress::Item', "$form_item[item_name]::$form_id", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation. 
    if (empty($confirmation)) {
        // No confirmation yet - display a suitable form to obtain confirmation

        $render = pnRender::getInstance('FormExpress');
        $render->assign($form_item);
        return $render->fetch('formexpress_admin_item_delete.html');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code.  This checks that the form had a valid
    // authorisation code attached to it.  If it did not then the function will
    // proceed no further as it is possible that this is an attempt at sending
    // in false data to the system
    if (!pnSecConfirmAuthKey()) {
        $render = pnRender::getInstance('FormExpress');
        return $render->fetch('formexpress_user_submit_form_badkey.html');
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
    if (pnModAPIFunc('FormExpress', 'admin', 'item_delete',
                     array('form_item_id' => $form_item_id))) {
        // Success

        //We just want to clear the cache for this form.
        $fxCache = new FXCache(false);
        $fxCache->delForm($form_item['form_id']);

        $dom = ZLanguage::getModuleDomain('FormExpress');
        pnSessionSetVar('statusmsg', __('Item deleted.', $dom));
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

/**
 * Create example forms
 * @param None
 */
function FormExpress_admin_createexample($args)
{
    $ModName = basename( dirname( __FILE__ ) );
    
    $files = array ('example.fxm', 'example2.fxm');
    foreach ($files as $file) {
        $file = "modules/$ModName/$file";
        $form = pnModAPIFunc('FormExpress', 'admin', 'serialize2form', array ('import_file_name' => $file));
        pnModAPIFunc('FormExpress', 'admin', 'loadform', array ('form' => $form) );
    }

    $dom = ZLanguage::getModuleDomain('FormExpress');
    pnSessionSetVar('statusmsg', __('The example forms were created', $dom));

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    pnRedirect( pnModURL( 'FormExpress', 'admin', 'view' ) );
    
    // Return
    return true;
}

function FormExpress_admin_shift_item_weight($args) {

    $form_id = FormUtil::getPassedValue('form_id');
    $form_item_id = FormUtil::getPassedValue('form_item_id');
    $action = FormUtil::getPassedValue('action');

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

    if (!pnModAPIFUnc('FormExpress','admin', 'shift_item_weight',
            array( 'form_id' => $form_id, 'form_item_id' => $form_item_id, 'action' => $action )
        ) ) {
        return pnSessionGetVar('errormsg');
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

?>
