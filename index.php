<?php
    if (!defined("LOADED_AS_MODULE"))
    {
        die ("You can't access this file directly...");
    }
    pnRedirect(pnModURL( 'FormExpress'
                       , 'user'
                       , 'display_form'
                       , array( 'form_id' => pnModGetVar('FormExpress', 'default_form_id')
                              )
                       )
              );
?>

