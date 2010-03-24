<?php

class formexpress_admin_modifyconfigHandler extends pnFormHandler
  {
    
    /* Global variables here */
        
    /* Functions */


    function initialize(&$render)
    {

      /* Read the (currently only) config value */
      $default_form_id = pnModGetVar('FormExpress', 'default_form_id');
      $render->assign('default_form_id', $default_form_id);

      $render->assign('languageItems', array(array('text' => 'All', 'value' => '')) );
      $forms = pnModAPIFunc( 'FormExpress', 'user', 'getall' );
      $formlist = array();
      $formlist[] = array( 'text' => '', 'value' => '-1');
      foreach( $forms as $id => $form ) {
        $formlist[] = array( 'text' => $form['form_name'], 'value' => $form['form_name'] );
      }
      $render->assign('default_form_idItems', $formlist);  
      return true;
    }
    
    function handleCommand(&$render, &$args)
    {
    
      if (!$render->pnFormIsValid()) return false;
      
      $formData = $render->pnFormGetValues();
      pnModSetVar('FormExpress', 'default_form_id', $formData['default_form_id'] );
      pnSessionSetVar('statusmsg', __('Configuration has been saved.') );
      return true;
//      return pnRedirect (pnModURL('FormExpress', 'admin', 'view'));
      
    }


  }
