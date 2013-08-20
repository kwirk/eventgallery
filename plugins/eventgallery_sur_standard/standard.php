<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class EventgalleryPluginsSurchargeStandard extends  EventgalleryLibraryMethodsSurcharge
{


    /**
     * Returns if this method can be used with the current cart.
     *
     * @param EventgalleryLibraryLineitemcontainer $cart
     *
     * @return bool
     */
    public function isEligible($cart)
    {
        // if there is no rule, this method is valued
        if (!isset($this->getData()->rules)) {
            return true;
        }

        // if the minimum amount is not defined skip this
        if (isset($this->getData()->rules->minAmount)) {
            // if the subtotal is not high enough
            if ($cart->getSubTotal()->getAmount()<$this->getData()->rules->minAmount ) {
                return false;
            }
        }

        // if the maximum amount is not defined skip this
        if (isset($this->getData()->rules->maxAmount) && $this->getData()->rules->maxAmount>0) {
            // if the subtotal is too high
            if ($cart->getSubTotal()->getAmount()>$this->getData()->rules->maxAmount ) {
                return false;
            }
        }

        return true;
    }

    static public  function getClassName() {
        return "Surcharge: Standard";
    }

    public function onPrepareAdminForm($form) {

        /**
         * add the language files
         */

        $language = JFactory::getLanguage();
        $language->load('plg_eventgallery_sur_standard' , __DIR__ , $language->getTag(), true);

        /**
         * disable the default data field
         */
        $form->setFieldAttribute('data', 'required', 'false');
        $form->setFieldAttribute('data', 'disabled', 'true');

        $field = new SimpleXMLElement('
            <fieldset name="surcharge" label="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_LABEL" description="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_DESC">
                <field name="surcharge_standard_min"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_MIN_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_MIN_DESC"
                   required="false"
                   class="input-xlarge"
                />
                <field name="surcharge_standard_max"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_MAX_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_SURCHARGE_STANDARD_MAX_DESC"
                   required="false"
                   class="input-xlarge"
                />
            </fieldset>
        ');
        $form->setField($field);

        if (isset($this->getData()->rules->minAmount)) {  $form->setValue("surcharge_standard_min", null, $this->getData()->rules->minAmount); }
        if (isset($this->getData()->rules->maxAmount)) {  $form->setValue("surcharge_standard_max", null, $this->getData()->rules->maxAmount); }

        return $form;
    }

    public function onSaveAdminForm($data) {

        $object = new stdClass();

        $object->rules = array (
            "minAmount"=>(float)$data['surcharge_standard_min'],
            "maxAmount"=>(float)$data['surcharge_standard_max'],
        );

        $this->setData($object);

        return true;
    }
}