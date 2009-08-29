<?php
/** ********************************************************************************
 * This class extends pnHTML, overridding some of the less well defined functions
 *
 **/
class FXHtml extends pnHTML {

    var $fx_tabindex;

   /** *****************************************************************************
    *  Constructor
    **/
    function fxHTML() {
        //Call the parent constructor
        $this->pnHTML();
        static $static_tabindex;
        if ( $static_tabindex === null ) {
            $static_tabindex = 1;
        }
        $this->fx_tabindex =& $static_tabindex;
    }

    /**
     * Add HTML tags for a submission button as part of a form.
     *
     * @access public
     * @param string $label (optional) the name of the submission button.  This
     * defaults to <code>'Submit'</code>
     * @param string $accesskey (optional) accesskey to active this button
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormSubmit( $fieldname='submit'
                       , $submit_type='submit'
                       , $label='Submit'
                       , $text_align='top'
                       , $accesskey=''
                       , $field_attributes=''
                       )
    {
        $this->fx_tabindex++;
        $output = '<input'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' type="'.$submit_type.'"'
            .' value="'.pnVarPrepForDisplay($label).'"'
            .' align="'.$text_align.'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->fx_tabindex.'"'
            .' '.$field_attributes
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a text field as part of a form.
     *
     * @access public
     * @param string $fieldname the name of the text field
     * @param string $contents (optional) the inital value of the text field
     * @param integer $size (optional) the size of the text field on the page
     * in number of characters
     * @param integer $maxlength (optional) the maximum number of characters the
     * text field can hold
     * @param boolean $password (optional) field acts as a password field
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormText( $fieldname
                     , $contents=''
                     , $size=16
                     , $maxlength=64
                     , $password=false
                     , $accesskey=''
                     , $field_attributes=''
                     )
    {
        if (empty ($fieldname))
        {
            return;
        }
        $this->fx_tabindex++;
        $output = '<input'
            .' type="'.(($password) ? 'password' : 'text').'"'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' value="'.pnVarPrepForDisplay($contents).'"'
            .' size="'.pnVarPrepForDisplay($size).'"'
            .' maxlength="'.pnVarPrepForDisplay($maxlength).'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->fx_tabindex.'"'
            .' '.$field_attributes
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a text area as part of a form
     *
     * @access public
     * @param string $fieldname the name of the text area filed
     * @param string $contents the initial value of the text area field
     * @param integer $rows the number of rows that the text area
     |        should cover
     * @param integer $cols the number of columns that the text area
     |        should cover
     * @param string $wrap (optional) wordwrap mode to use, either <code>'soft'</code> or <code>'hard'</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormTextArea( $fieldname
                         , $contents=''
                         , $rows=6
                         , $cols=40
                         , $wrap='soft'
                         , $accesskey=''
                         , $field_attributes=''
                         )
    {
        if (empty ($fieldname))
        {
            return;
        }
        $this->fx_tabindex++;
        $output = '<textarea'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' wrap="'.(($wrap = 'soft') ? 'soft' : 'hard').'"' // not proper HTML, but too useful to abandon yet
            .' rows="'.pnVarPrepForDisplay($rows).'"'
            .' cols="'.pnVarPrepForDisplay($cols).'"'
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->fx_tabindex.'"'
            .' '.$field_attributes
            .'>'
            .pnVarPrepForDisplay($contents)
            .'</textarea>'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a select field as part of a form.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $fieldname the name of the select field
     * @param array $data an array containing the data for the list.  Each array
     * entry is itself an array, containing the values for <code>'id'</code>
     * (the value returned if the entry is selected), <code>'name'</code>
     * (the string displayed for this entry) and <code>'selected'</code>
     * (optional, <code>1</code> if this option is selected)
     * @param integer $multiple (optional) <code>1</code> if the user is allowed to
     * make multiple selections
     * @param integer $size (optional) the number of entries that are visible in the
     * select at any one time.  Note that if the number
     * of actual items is less than this value then the select box will
     * shrink automatically to the correct size
     * @param string $selected (optional) selected value of <code>id</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormSelectMultiple( $fieldname
                               , $data
                               , $multiple=0
                               , $size=1
                               , $selected = ''
                               , $accesskey=''
                               , $field_attributes=''
                               )
    {
        if (empty ($fieldname))
        {
            return;
        }
        $this->fx_tabindex++;

        // Set up selected if required
        if (!empty($selected)) {
            for ($i=0; !empty($data[$i]); $i++) {
                if ($data[$i]['id'] == $selected) {
                    $data[$i]['selected'] = 1;
                }
            }
        }

        $c = count($data);
        if ($c < $size)
        {
            $size = $c;
        }
        $output = '<select'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).'"'
            .' size="'.pnVarPrepForDisplay($size).'"'
            .(($multiple == 1) ? ' multiple="multiple"' : '')
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->fx_tabindex.'"'
            .' '.$field_attributes
            .'>'
        ;
        foreach ($data as $datum)
        {
            $output .= '<option'
                .' value="'.pnVarPrepForDisplay($datum['id']).'"'
                .((empty ($datum['selected'])) ? '' : ' selected="selected"')
                .'>'
                .pnVarPrepForDisplay($datum['name'])
                .'</option>'
            ;
        }
        $output .= '</select>';
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

    /**
     * Add HTML tags for a checkbox or radio button field as part of a form.
     *
     * @access public
     * @since 1.13 - 2002/01/23
     * @param string $fieldname the name of the checkbox field
     * @param string $value (optional) the value of the checkbox field
     * @param boolean $checked (optional) the field is checked
     * @param string $type (optional) the type of field this is, either
     * <code>'checkbox'</code> or <code>'radio'</code>
     * @param string $accesskey (optional) accesskey to active this item
     * @return string An HTML string if <code>ReturnHTML()</code> has been called,
     * otherwise null
     */
    function FormCheckbox( $fieldname
                         , $checked=false
                         , $value='1'
                         , $type='checkbox'
                         , $accesskey=''
                         , $field_attributes=''
                         )
    {
        if (empty ($fieldname))
        {
            return;
        }
        $this->fx_tabindex++;
        $output = '<input'
            .' type="'.(($type == 'checkbox') ? 'checkbox' : 'radio').'"'
            .' name="'.pnVarPrepForDisplay($fieldname).'"'
            .' id="'.pnVarPrepForDisplay($fieldname).(($type == 'radio') ? pnVarPrepForDisplay($value) : '').'"'
            .' value="'.pnVarPrepForDisplay($value).'"'
            .(($checked) ? ' checked="checked"' : '')
            .((empty ($accesskey)) ? '' : ' accesskey="'.pnVarPrepForDisplay($accesskey).'"')
            .' tabindex="'.$this->fx_tabindex.'"'
            .' '.$field_attributes
            .' />'
        ;
        if ($this->GetOutputMode() == _PNH_RETURNOUTPUT)
        {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

}
?>
