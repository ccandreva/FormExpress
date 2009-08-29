<?php
/**
 * This class creates and maintains a cache of defined forms.
 *
 **/
class FXCache {

    /** **************************************************************************
     * $forms is an array of the form data, including an array of the item data
     * 
     * array ([form_id] = array( ['form_name'] = form_name
     *                           [ ... ]
     *                           ['items']     = array( 'item_id'   = item_id
     *                                                , 'item_name' = item_name
     *                                                , ...
     *                                                )
     *                         )
     *       )
     */
    var $dbconn;
    var $pntable;
    var $FormExpressCache;
    var $FormExpressCache_column;
    var $useCache;

    /** **************************************************************************
     * Constructor
     * Gets all the db and table info
     */
    function FXCache($useCache=true) {
        //FormExpress Module should already be loaded...
        if (!pnModDBInfoLoad('FormExpress')) {
            pnSessionSetVar('errormsg', _FAILEDTOLOADMODULE);
            return false;
        }
        list($this->dbconn) = pnDBGetConn();
        $this->pntable = pnDBGetTables();
        $this->FormExpressCache = $this->pntable['FormExpressCache'];
        $pntable = $this->pntable;
        $this->FormExpressCache_column = &$pntable['FormExpressCache_column'];

        $this->useCache = $useCache;

    }


    /** **************************************************************************
     * Puts a form into the cache
     * @private
     */
    function setForm($form_id) {
        if ((!isset($form_id))
           ) {
            pnSessionSetVar('errormsg', _MODARGSERROR);
            return false;
        }

        // Load API.  Note that this is loading the user API, that is because the
        // user API contains the function to obtain item information which is the
        // first thing that we need to do.  If the API fails to load an appropriate
        // error message is posted and the function returns
        if (!pnModAPILoad('FormExpress', 'user')) {
            $output->Text(_LOADFAILED);
            return $output->GetOutput();
        }

        $form = pnModAPIFunc('FormExpress'
                            , 'user'
                            , 'get'
                            , array( 'form_id' => $form_id
                                   )
                            );

        if ($form == false) {
            pnSessionSetVar('errormsg', _FORMEXPRESSNOFORMFOUND);
            return false;
        }

        $form['items'] = pnModAPIFunc('FormExpress'
                                     , 'user'
                                     , 'items_getall'
                                     , array( 'form_id' => $form_id
                                            , 'status' => 'active'
                                            )
                                     );

        //We don't care if no items (at this stage maybe valid)
        //if ($form['items'] == false) {
        //    pnSessionSetVar('errormsg', _FORMEXPRESSNOITEMSFOUND);
        //    //return false;
        //}


        if ( ($this->useCache) && ($form['active'])) {
            //// Insert into the cache for future use
            $sql = 'INSERT INTO '.$this->FormExpressCache
                  .'     ( '.$this->FormExpressCache_column['form_id']
                  .'     , '.$this->FormExpressCache_column['form_data']
                  .'     ) VALUES '
                  ."     ( '" . pnVarPrepForStore($form_id) . "'"
                  ."     , '" . pnVarPrepForStore(serialize($form)) . "'"
                  .'     )';
            $this->dbconn->Execute($sql);
            if ($this->dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _SETFAILED.$sql);
                return false;
            }
        }
        return $form;
    }

    /**
     * Gets a form from the cache - creates it if not found
     *
     */
    function getForm($form_id) {
        if ((!isset($form_id))
           ) {
            pnSessionSetVar('errormsg', _MODARGSERROR);
            return false;
        }
        if ( $this->useCache ) {
            $sql = 'SELECT '.$this->FormExpressCache_column['form_data']
                  .'  FROM '.$this->FormExpressCache
                  .' WHERE '.$this->FormExpressCache_column['form_id'].' = ' 
                           . pnVarPrepForStore($form_id);
            $result = $this->dbconn->Execute($sql);

            // Check for an error with the database code, and if so set an appropriate
            // error message and return
            if ($this->dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _GETFAILED.$sql);
                return false;
            }
            //Check to see if we got any rows
            if (!$result->EOF) {
                list($form_data_raw) = $result->fields;
                return unserialize($form_data_raw);
            } else { //populate the cache
                return $this->setForm($form_id);
            }
        } else { 
            $this->delForm($form_id);
            return $this->setForm($form_id);
        }
    }

    /**
     * Removes a form from the cache
     *
     */
    function delForm($form_id) {
        if ((!isset($form_id))
           ) {
            pnSessionSetVar('errormsg', _MODARGSERROR);
            return false;
        }
        $sql = 'DELETE FROM '.$this->FormExpressCache
              .' WHERE '.$this->FormExpressCache_column['form_id'].' = ' 
                       . pnVarPrepForStore($form_id);
        $result = $this->dbconn->Execute($sql);

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($this->dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _GETFAILED.$sql);
            return false;
        }
    }

}
?>
