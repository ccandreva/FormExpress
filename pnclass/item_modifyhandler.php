<?php

class formexpress_admin_item_modifyHandler extends pnFormHandler
  {
    
    /* Global variables here */

// require_once('item_type_display.php');
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

    var $item_type_display = array( 'boilerplate' => array( 'sequence'          => '0'
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
                                                      , 'required'          => '1'
                                                      , 'prompt'            => '1'
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
                              
    var $params;

    /* Functions */

    function setParams($params)
    {
      $this->params = $params;
    }

    function initialize(&$render)
    {
        $dom = ZLanguage::getModuleDomain('FormExpress');

        // create: form_id, item_type, required_sequence
        // modify: form_id, item_type, form_item_id

        /* Get item array from the pnRender/Smarty object */
//        $form_item = $render->get_template_vars('form_item');

        // Parameters should only be set when the form is intiailly called
        if (is_array($this->params)) {
            $func = $this->params['func'];

            if ( $func == 'modify') {
              // We only need to read the old parameters on the initial call to modify the form,
              // After that they are in the form itself.
              $form_item = pnModAPIFunc( 'FormExpress', 'admin', 'item_get', $this->params);
            } elseif ( $func == 'new' ) {
              // For new we can just load the set params, which have the needed ID.
              $form_item = $this->params;
            } else {
              // Otherwise it's an error
              return false;
            }
            $type = $form_item['item_type'];
            $form_id = $form_item['form_id'];
            $rs = $form_item['required_sequence'];
            SessionUtil::setVar('formexpress_item_modify', array ('func' => $func,
              'form_id' => $form_id, 'item_type' => $type,
              'form_item_id' => $form_item['form_item_id'],
              'sequence' => $form_item['sequence'],
              'required_sequence' => $rs ) );

        // Otherwise, we read the parameters from the session variable.
        } else {
            $form_item = SessionUtil::getVar('formexpress_item_modify');
            $type = $form_item['item_type'];
            $form_id = $form_item['form_id'];
        }

        $render->assign($form_item);

        if (!empty($type)) {
          $render->assign_by_ref('display', $this->item_type_display[$type]);
        }          

        // Set up drop-box of existing names if this is a radio button
        if ($type == 'radio') {
            $item_name_pickItems = pnModAPIFunc('FormExpress', 'admin', 'get_radio_input_list', 
              array('id' => $form_item['form_id']) );
            $item_name_pickItems[] = array('value' => 'newradiogroup', 'text' => __('--New Radio Group--', $dom) );
            $render->assign_by_ref('item_name_pickItems', $item_name_pickItems);
        }
        
        // Set up drop-box of prompt positions
        $prompt_positionItems = array (
            array( 'value' => 'above', 'text' => __('Above', $dom) ),
            array( 'value' => 'below', 'text' => __('Below', $dom) ),
            array( 'value' => 'leftcol', 'text' => __('Left Column', $dom) ),
            array( 'value' => 'left', 'text' => __('Left', $dom) ),
            array( 'value' => 'right', 'text' => __('Right', $dom) ),
            array( 'value' => 'hidden', 'text' => __('Hidden', $dom) ),
            );
        $render->assign_by_ref('prompt_positionItems', $prompt_positionItems);
            
        // Set up drop-box of relative positions
        $relative_positionItems = array (
            array( 'value' => 'below', 'text' => __('Below', $dom) ),
            array( 'value' => 'right', 'text' => __('Right', $dom) ),
            array( 'value' => 'inline', 'text' => __('Inline', $dom) ),
            );
        $render->assign_by_ref('relative_positionItems', $relative_positionItems);


      return true;
    }
    
    function handleCommand(&$render, &$args)
    {
    
      if (!$render->pnFormIsValid()) return false;
            
      $formData = array_merge($render->pnFormGetValues(), SessionUtil::getVar('formexpress_item_modify'));
      $form_id = $formData['form_id'];
      SessionUtil::delVar('formexpress_item_modify');

      $item_name_pick = $formData['item_name_pick'];
      unset($formData['item_name_pick']);
      if ($item_name_pick != 'newradiogroup') {
          $form['item_name'] = $item_name_pick;
      }
      
      
      if ($formData['func'] == 'new' ) {
        $func = 'item_create';
      } else {
        $func = 'item_update';
      }
        $stat = pnModAPIFunc('FormExpress', 'admin', $func,
                array('itemObj' => $formData) );
      if ($stat == false) {
        $render->assign('errormsg', __('An error occurred saving the form: ') . $form_id . ' ' . pnSessionGetVar('errormsg') ); 
	return false;
      }

      // Remove cached copy of form.
      $fxCache = new FXCache();
      $fxCache->delForm($form_id);
      pnSessionSetVar('statusmsg', __('Your item has been saved.') );

      return pnRedirect (pnModURL('FormExpress', 'admin', 'items_view', array('form_id'=>$form_id) ) );
      
    }


  }
