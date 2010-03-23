<?php
/* 
 * Function to show form input fields 
 */

function smarty_function_feshowinput($params, &$smarty)
{

  if ($params['item']) $item = $params['item'];
  else return "Error\n";
  
  $name = $item['item_name'] . $params['suffix'];
  $type = $item['item_type'];
  $field_attributes = $item['item_attributes'];
  
  // Find a value for this field, could be in one of three places.
  foreach (array('user_data', 'default_value', 'item_value') as $v) {
    if ( isset($item[$v]) && $item[$v] != '') {
      $value = $item[$v];
      break;
     }
  }

  switch ($type) {

    /* For boilerplate we just return the value */
    case 'boilerplate' :
      return $value;
      
    /* Select lists need to be built from the parts */
    case 'selectlist' :
      $input = '<select name="' . $name . '" id="' . $name .'" '
        . feshowinput_formatopt('size', $item['rows'], '1')
//        . feshowinput_formatopt('multiple', $item['multiple'])
        . $field_attributes
        . ">\n";
      
      $lov = explode(',',$value);
      foreach($lov as $vals) {
        $parts = explode('=', $vals);
        $input .= '<option value="' . $parts[0] . '" ';
        if ($parts[0] == $value) $input .= 'select ';
        $input .= '>';
        if (isset($parts[1])) $input .= $parts[1];
        else $input .= $parts[0];
        $input .= "</option>\n";
      }
      $input .= "</select>\n";
      
      break;

    /* Passwords should never have a default value */
    case 'password' :
      unset($value);

    /* Passwords and text fields have size and length */
    case 'text' :
      $attributes .= feshowinput_formatopt('size', $item['cols'], 16);
      $attributes .= feshowinput_formatopt('maxlength', $item['max_length'], 64);

    /* Now for everything that isn't a selectlist */
    default:

      /* Textareas are a different tag style, so we set those up here.*/
      if ($type == 'textarea') {
        $input = '<textarea ';
        $end = $value . '</textarea>';
        unset($value);
        $attributes .= feshowinput_formatopt('rows', $item['rows'], 6);
        $attributes .= feshowinput_formatopt('cols', $item['cols'], 40);
      }
      /* Otherwise, we use an input of the correct type */
      else {
        $input = '<input type="' . $type .'" ';
      }
      
      /* If there is a value, set a value parameter. */
      if (isset($value)) {
        $value = 'value="' . $value . '" ';
      }

      /* Read and increment tabindex */
      $tabindex = $smarty->get_template_vars('tabindex') + 1;
      $smarty->assign('tabindex', $tabindex);

      /* Build the rest of the field with the options collected */
      $input .= 'name="' . $name . '" '
        . 'id="' . $name . '" '
        . 'tabindex="' . $tabindex . '" '
        . $value . $attributes . $field_attributes
        . '/>' . $end . "\n";
  }

  
  /* Set up the prompt, if one exists */
  if (!empty($item['prompt'])) {
    $promptpos = $item['prompt_position'];
    $label = '<label for="' . $name . '" class="' . $promptpos . '">' . $item['prompt'] . "</label>\n";

    /* The order of prompt and field changes depending on some positions */
    switch($promptpos) {
      case 'below' :
      case 'right' :
        $input .= $label;
        break;
        
      default :
        $input = $label . $input;
    }
  }

  /* And finally, return the field we have built. */
  return $input;

 }
 

function feshowinput_formatopt($tag, $value, $default)
{
    if ( ($value == '') && ($default != '') ) $value = $defualt;
    if ($value != '') return $tag . '="' . $value . '" ';
}
