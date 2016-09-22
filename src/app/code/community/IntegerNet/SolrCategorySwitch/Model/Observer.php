<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_SolrCategorySwitch
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
class IntegerNet_SolrCategorySwitch_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @event controller_action_predispatch_catalog_category_view
     */
    public function controllerActionPredispatchCatalogCategoryView(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Controller_Varien_Action $action */
        $action = $observer->getControllerAction();

        if (Mage::getStoreConfigFlag('integernet_solr/general/is_active')
            && Mage::getStoreConfigFlag('integernet_solr/category/is_active')
            && $this->_isCategoryExcluded($action)) {

            Mage::app()->getStore()->setConfig('integernet_solr/category/is_active', 0);
        }
    }

    /**
     * @param Mage_Core_Controller_Varien_Action $action
     * @return bool
     */
    protected function _isCategoryExcluded($action)
    {
        $categoryId = $action->getRequest()->getParam('id');
        
        return Mage::getResourceModel('catalog/category')->getAttributeRawValue($categoryId, 'solr_dont_use_for_display', Mage::app()->getStore()->getId());
    }
}