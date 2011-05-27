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
 * Purpose of file: Show Form in a block
 * ----------------------------------------------------------------------
 */
 
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
    if (!SecurityUtil::checkPermission('FormExpresss:formblock:', '$blockinfo[title]::', ACCESS_READ)) {
        return;
    }
    
    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    $form_id = $vars['form_id'];
    
    // Check for a form id
    if (empty($form_id)) {
        return __('No Form has been specified for this block');
    }

    // Populate block info and pass to theme
    $blockinfo['content'] = pnModFunc( 'FormExpress', 'user', 'display_form',
               array( 'form_id' => $form_id, 'admin_mode' => false)
            );

    return themesideblock($blockinfo);
}


/**
 * modify block settings
 */
function FormExpress_formblock_modify($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['form_id'])) {
        $form_id = pnModGetVar('FormExpress', 'default_form_id');
    } else {
        $form_id = $vars['form_id'];
    }

    // Get the form list
    $forms = pnModAPIFunc( 'FormExpress', 'user', 'getall');

    $render = pnRender::getInstance('FormExpress');

    // Pre-select current form
    if (isset($forms[$form_id])) {
        $forms[$form_id][selected] = 1;
    }
    $render->assign('forms', $forms);
    
    return $render->fetch('formexpress_block_formblock_modify.html');;
}

/**
 * update block settings
 */
function FormExpress_formblock_update($blockinfo)
{
    //$vars['form_id'] = pnVarCleanFromInput('form_id');
    $vars['form_id'] = FormUtil::getPassedValue('form_id');
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}

?>
