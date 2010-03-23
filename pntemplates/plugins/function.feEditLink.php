<?php
/* 
 * Function to show form input fields 
 */

/** 
 * Edit, Delete, Up, Down 
 * xedit, 14_layer_deletelayer.gif, 2uparrow, 2downarrow
*  null, null, !isfirst, !islast
 */

function smarty_function_feEditLink($params, &$smarty)
{

  $dom = ZLanguage::getModuleDomain('FormExpress');

  if ($params['item']) $item = $params['item'];
  else return '<em>'. __('Missing form ID', $dom) . '</em>';
  
  $URLopt = array( 'form_id'     => $item['form_id'], 
                  'form_item_id' => $item['form_item_id'],
                  'authid'       => pnSecGenAuthKey()
                  );

  $output = '<a href= "' . pnModURL('FormExpress', 'admin', 'item_modify', $URLopt) . '">'
    . '<img src="images/icons/extrasmall/xedit.gif" alt="' . __('Edit', $dom) . '"></a>'
    . '<a href= "' . pnModURL('FormExpress', 'admin', 'item_delete', $URLopt) . '">'
    . '<img src="images/icons/extrasmall/14_layer_deletelayer.gif" alt="' . __('Delete', $dom) . '"></a>';
    
  // Only show the up/down links for active items.
  if ($item['active']) {
    if (!$item['isfirst']) {
      $URLopt['action'] = 'lighter';
      $output .= '<a href= "' . pnModURL('FormExpress', 'admin', 'shift_item_weight', $URLopt) . '">'
        . '<img src="images/icons/extrasmall/2uparrow.gif" alt="' . __('Move Up', $dom) . '"></a>';
    }
    if (!$item['islast']) {
      $URLopt['action'] = 'heavier';
      $output .= '<a href= "' . pnModURL('FormExpress', 'admin', 'shift_item_weight', $URLopt) . '">'
        . '<img src="images/icons/extrasmall/2downarrow.gif" alt="' . __('Move Down', $dom) . '"></a>';
    }
  }

  /* And finally, return the field we have built. */
  return $output;

 }
