<?php

class formexpress_admin_importexportHandler extends pnFormHandler
  {
    
    /* Global variables here */
        
    /* Functions */


    function initialize(&$render)
    {

      $forms = pnModAPIFunc( 'FormExpress', 'user', 'getall' );
      $formlist = array();
      // $formlist[] = array( 'text' => '', 'value' => '-1');
      foreach( $forms as $id => $form ) {
        $formlist[] = array( 'text' => $form['form_name'], 'value' => $form['form_id'] );
      }
      $render->assign('export_form_idItems', $formlist);  
      return true;
    }
    
    function handleCommand(&$render, &$args)
    {
    
      if (!$render->pnFormIsValid()) return false;
      
      $formData = $render->pnFormGetValues();
      $import_file_name = $formData['import_file_name'];

      // Needed for php4 support
      if (is_array ($import_file_name)) {
        $tmp_name = $import_file_name['tmp_name'];
      } else {
        $tmp_name = $import_file_name;
      }

      pnSessionSetVar('statusmsg', $tmp_name );
      $form = FormExpress_serialize2form($tmp_name);
      // Originally an error was return if form_name is set, but I can't see
      // why that is needed, or how this is usefull if that is blocked.
      FormExpress_loadform($form);
      return true;
      
    }


  }
