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
    var $useCache;

    /** **************************************************************************
     * Constructor
     * @param useCache: Enable/Disable caching
     */
    function FXCache($useCache=true) {
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

        $form = pnModAPIFunc('FormExpress', 'user', 'get'
                            , array( 'form_id' => $form_id)
                            );

        if ($form == false) {
            pnSessionSetVar('errormsg', _FORMEXPRESSNOFORMFOUND);
            return false;
        }

        $form['items'] = pnModAPIFunc('FormExpress', 'user', 'items_getall'
                                     , array( 'form_id' => $form_id
                                            , 'status' => 'active'
                                            )
                                     );


        if ( ($this->useCache) && ($form['active'])) {
            //// Insert into the cache for future use
            $cacheObj = array ( 'id' => $form_id, 'data' => serialize($form));
            $result = DBUtil::insertObject($cacheObj, 'FormExpressCache', true);
            if (!$result) {
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
            $cacheObj = DBUtil::selectObjectByID('FormExpressCache', $form_id);

            //Check to see if we got an object
            if ($cacheObj) {
                return unserialize($cacheObj['data']);
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
        DBUtil::deleteObjectByID('FormExpressCache', $form_id);
    }

}
?>
