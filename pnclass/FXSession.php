<?php
class FXSession {

    /**
     * $forms is an array of the form(s) user data
     */
    var $forms;
    var $submitted_form;

    /** ****************************************************************************
     * Constructor
     * Gets the FormExpress Session variables (if any)
     */
    function FXSession() {

        $this->forms = pnSessionGetVar('FormExpressData');
        if ( empty($this->forms) ) {
            pnSessionSetVar('FormExpressData', $this->forms);
        }

    }


    /** ****************************************************************************
     * Set user form data
     */
    function setForm($form_id, $user_data, $submitted_form = false ) {
        if (!$form_id) {
            pnSessionSetVar('errormsg', _MODARGSERROR.' setForm');
            return false;
        }
        $this->forms[$form_id] = $user_data;
        pnSessionSetVar('FormExpressData', $this->forms);

        if ($submitted_form) {
            pnSessionSetVar('FormExpressSubmittedForm', $form_id);
        }
    }

    /** ****************************************************************************
     * Get user form data
     */
    function getForm($form_id='') {
        //$this->forms = pnSessionGetVar('FormExpressData');
        if (!$form_id) { 
            $form_id = pnSessionGetVar('FormExpressSubmittedForm'); 
        }
        if ( ( count($this->forms) > 0 )  
           &&( array_key_exists($form_id, $this->forms) )
           ) {
            return $this->forms[$form_id];
        } else {
            return false;
        }
    }

    /** ****************************************************************************
     * Not used
     */
    function getForms() {
        return $this->forms;
    }

    /** ****************************************************************************
     * Remove a user form data from the session
     */
    function delForm($form_id) {
        if ( ( is_array($this->forms) ) 
           &&( array_key_exists($form_id, $this->forms) ) 
           ) {
            unset($this->forms[$form_id]);
            pnSessionSetVar('FormExpressData', $this->forms);
            return true;
        }
            return false;
    }

    /** ****************************************************************************
     * Get the last submitted form ID
     */
    function getSubmittedFormID() {
        return pnSessionGetVar('FormExpressSubmittedForm');
    }
}
?>
