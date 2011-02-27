<?php

class formexpress_admin_modifyformHandler extends pnFormHandler
  {
    
    /* Global variables here */
    var $form_id = false;
        
    /* Functions */

    function SetFormId($form_id) {
      $this->form_id = $form_id;
    }

    function initialize(&$render)
    {

        $fxCache = new FXCache(false);
        if($this->form_id) {
          $fxCache = new FXCache(false);
          $form = $fxCache->getForm($this->form_id);
          pnSessionSetVar('formexpress_modifyform_formid', $this->form_id);
        } else {
          pnSessionDelVar('formexpress_modifyform_formid');
          $form = array(
        	'submit_action'  => "{FormExpress:sendmail&email_address='". pnConfigGetVar('adminmail') ."'}",
        	'success_action' => "{FormExpress:display_message&type='success'&message='Your message has been sent'}",
        	'failure_action' => "{FormExpress:display_message&type='failure'&message='Ooops! Something horrid has happened!'}",
        	'active' => '1',
	  );
        }
    	$render->assign($form);
	$render->assign('languageItems', array(array('text' => 'All', 'value' => '')) );

      return true;
    }
    
    function handleCommand(&$render, &$args)
    {
    
      if (!$render->pnFormIsValid()) return false;
      
      $formData = $render->pnFormGetValues();
      if (!isset($formData['language'])) $formData['language'] = 'All';

      $form_id = pnSessionGetVar('formexpress_modifyform_formid');
      if (empty($form_id)) {
        $stat = pnModAPIFunc('FormExpress', 'admin', 'create', 
                array('formData' => $formData) );
      } else {
        pnSessionDelVar('formexpress_modifyform_formid');
        $formData[form_id] = $form_id;
        $stat = pnModAPIFunc('FormExpress', 'admin', 'update',
                array('formData' => $formData) );
      }

      if ($stat == false) {
        $render->assign('errormsg', __('An error occurred saving the form: ') . $form_id . ' ' . pnSessionGetVar('errormsg') ); 
	return false;
      }

      pnSessionSetVar('statusmsg', __('Your form has been saved.') );
      pnSessionDelVar('formexpress_modifyform_formid');
      return pnRedirect (pnModURL('FormExpress', 'admin', 'view'));
      
    }


  }
