<?php
    $sql = "INSERT INTO $FormExpresstable
               ( $FormExpresscolumn[form_id]
               , $FormExpresscolumn[form_name]
               , $FormExpresscolumn[description]
               , $FormExpresscolumn[action_source]
               , $FormExpresscolumn[action_name]
               , $FormExpresscolumn[action_args]
               , $FormExpresscolumn[success_message]
               , $FormExpresscolumn[failure_message]
               , $FormExpresscolumn[active]
               , $FormExpresscolumn[language]
               , $FormExpresscolumn[input_name_suffix]
               ) VALUES ( 1
                        , 'Sample Form'
                        , 'An example or FormExpress'
                        , 'FormExpress'
                        , 'email'
                        , 'anybody\@example.com'
                        , 'Your message has been sent'
                        , 'Oooops! A horrid error occurred - please call us on...'
                        , 1
                        , ''
                        , '1024590189'
                        )";
    $dbconn->Execute($sql);
    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _FORMEXPRESSEXAMPLEINSERTFAILED . $sql);
        return false;
    }
    $sql = "INSERT INTO nuke_FormExpressItems 
                 ( $FormExpressItemcolumn[form_item_id]
                 , $FormExpressItemcolumn[form_id]
                 , $FormExpressItemcolumn[sequence]
                 , $FormExpressItemcolumn[item_type]
                 , $FormExpressItemcolumn[item_name]
                 , $FormExpressItemcolumn[required]
                 , $FormExpressItemcolumn[prompt]
                 , $FormExpressItemcolumn[prompt_position]
                 , $FormExpressItemcolumn[item_value]
                 , $FormExpressItemcolumn[default_value]
                 , $FormExpressItemcolumn[cols]
                 , $FormExpressItemcolumn[rows]
                 , $FormExpressItemcolumn[max_length]
                 , $FormExpressItemcolumn[multiple]
                 , $FormExpressItemcolumn[class_name]
                 , $FormExpressItemcolumn[active]
                 , $FormExpressItemcolumn[relative_position]
                 ) VALUES 
                 ( 1, 1, 10, 'boilerplate', 'BoilerPlate', 0, '', 'hidden'
                   , 'For further information on the services we provide please contact A N Other:\r\n<p>\r\nPhone: 555 1234<br>\r\nFax: 555 5678<br>\r\nE-mail: <a href=\"email:another\@example.com\">another\@example.com</a>\r\n<p/>\r\nAlternatively, please complete the following form. We endeavour to respond to all enquirys within two working days. If however you require an urgent response please telephone us.\r\n'
                   , '', 0, 0, 0, 0, '', 1, 'below')
                 , (2, 1, 30, 'text', 'fullname', 1, 'Name', 'leftcol', '', ''
                   , 50, 0, 80, 0, '', 1, 'below')
                 , (3, 1, 40, 'text', 'companyname', 0, 'Company', 'leftcol'
                   , '', '', 50, 0, 80, 0, '', 1, 'below')
                 , (4, 1, 50, 'text', 'email', 1, 'Email Address', 'leftcol'
                   , '', '', 50, 0, 100, 0, '', 1, 'below')
                 , (5, 1, 60, 'text', 'phone', 1, 'Telephone', 'leftcol'
                   , '', '', 30, 0, 50, 0, '', 1, 'below')
                 , (6, 1, 70, 'boilerplate', 'BoilerPlate', 0, '', 'hidden'
                   , 'How would you prefer us to contact you?', '', 0, 0, 0, 0, '', 1, 'below')
                 , (7, 1, 80, 'radio', 'contactmethod', 0, 'Email', 'left'
                   , 'email', 'email', 0, 0, 0, 0, '', 1, 'inline')
                 , (8, 1, 90, 'radio', 'contactmethod', 0, 'Telephone', 'left'
                   , 'phone', '', 0, 0, 0, 0, '', 1, 'inline')
                 , (9, 1, 20, 'groupstart', 'GroupStart', 0, '<b>Your contact details</b>', 'above'
                   , '', '', 0, 0, 0, 0, '', 1, 'below')
                 , (10, 1, 100, 'groupend', 'GroupEnd', 0, '', '', '', '', 0, 0, 0, 0, '', 1, '')
                 , (11, 1, 110, 'groupstart', 'GroupStart', 0, '', 'hidden'
                   , '<b>Your area(s) of interest</b>', '', 0, 0, 0, 0, '', 1, 'below')
                 , (12, 1, 120, 'checkbox', 'ltdco', 0, 'Limited Companies', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'below')
                 , (13, 1, 130, 'checkbox', 'soletrader', 0, 'Sole Traders & Partnerships', 'right'
                   , 'soletrader', '', 0, 0, 0, 0, '', 1, 'right')
                 , (14, 1, 140, 'checkbox', 'newbusiness', 0, 'New Business Startups', 'right'
                   , 'newbusiness', '', 0, 0, 0, 0, '', 1, 'below')
                 , (15, 1, 150, 'checkbox', 'Compcontr', 0, 'Computer Contractors', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'right')
                 , (16, 1, 160, 'checkbox', 'taxcompliance', 0, 'Tax Compliance &amp; Planning', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'below')
                 , (17, 1, 170, 'checkbox', 'vat', 0, 'VAT', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'right')
                 , (18, 1, 180, 'checkbox', 'payroll', 0, 'Payroll', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'below')
                 , (19, 1, 190, 'checkbox', 'other', 0, 'Other:', 'right'
                   , 'yes', '', 0, 0, 0, 0, '', 1, 'right')
                 , (20, 1, 200, 'text', 'othertext', 0, '', 'hidden'
                   , '', '', 20, 0, 100, 0, '', 1, 'inline')
                 , (21, 1, 210, 'groupend', 'GroupEnd', 0, '', '', '', '', 0, 0, 0, 0, '', 1, '')
                 , (22, 1, 230, 'textarea', 'feedback', 0, '<b>Comments/Additional Information', 'above'
                   , '', '', 50, 5, 200, 0, '', 1, 'below')
                 , (23, 1, 220, 'groupstart', 'GroupStart', 0, '', 'hidden'
                   , '', '', 0, 0, 0, 0, '', 1, 'below')
                 , (24, 1, 240, 'groupstart', 'GroupStart', 0, '', 'hidden'
                   , '', '', 0, 0, 0, 0, '', 1, 'right')
                 , (25, 1, 250, 'selectlist', 'prstuff', 0, '<br>How did you hear about us?', 'above'
                   , ',1=Advert,2=Recomendation,3=Internet,Other', '', 0, 0, 0, 0, '', 1, 'below')
                 , (26, 1, 260, 'submit', 'submit', 0, '', 'above'
                   , 'Send', '', 0, 0, 0, 0, '', 1, 'below')
                 , (27, 1, 270, 'reset', 'reset', 0, '', 'hidden'
                   , 'Reset', '', 0, 0, 0, 0, '', 1, 'inline')
                 , (30, 1, 280, 'groupend', 'GroupEnd', 0, '', '', '', '', 0, 0, 0, 0, '', 1, '')
                 , (31, 1, 290, 'groupend', 'GroupEnd', 0, '', '', '', '', 0, 0, 0, 0, '', 1, '')
                 , (32, 1, 300, 'hidden', 'cantseeme', 0, '', '', '', 'well maybe', 0, 0, 0, 0, '', 1, '')
                 ";
    $dbconn->Execute($sql);
    // Check for an error with the database code, and if so set an
    // appropriate error message and return
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _FORMEXPRESSEXAMPLEINSERTFAILED . $sql);
        return false;
    }

?>
