<?php
/**
 * $Id:                                                             $
 *
 * Modifier to return 1 only if the input is 1, else 0
 */
 
function smarty_modifier_feIsMandatory($bool)
{
  if ($bool == 1) return 1;
  return 0;
}
