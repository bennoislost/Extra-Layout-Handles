<?php

class Bennoislost_ExtraLayoutHandles_Model_Observer
{
    /**
     * @param Varien_Event_Observer $event
     */
    public function addCategoryPathHandle(Varien_Event_Observer $event)
    {
        $category = Mage::registry('current_category');

        if (!$category && !$category instanceof Mage_Catalog_Model_Category) {
            return;
        }

        /** @var Mage_Core_Model_Layout $layout */
        $layout = $event->getEvent()->getLayout();

        $object = new Bennoislost_ExtraLayoutHandles_Model_Layout_Handle_Category(
            $category,
            $layout
        );

        $object->updateLayoutHandles();
    }

    /**
     * Adds layout handle PRODUCT_ATTRIBUTE_SET_<attribute_set_nicename>.
     *
     * @event controller_action_layout_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function addAttributeSetHandle(Varien_Event_Observer $observer)
    {
        $handlePrefix = 'PRODUCT_ATTRIBUTE_SET';

        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::registry('current_product');

        //Return if it is not product page
        if (!$product instanceof Mage_Catalog_Model_Product) {
            return;
        }

        //load the attribuet set
        $attributeSetName = Mage::getModel('eav/entity_attribute_set')
            ->load($product->getAttributeSetId())->getAttributeSetName();
        $attributeSetName = $this->_prepareHandle($attributeSetName);

        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();

        $update->addHandle($handlePrefix . '_' . $attributeSetName);
    }

    /**
     * Add handles for the dropdown attributes
     *
     * @param Varien_Event_Observer $observer
     */
    public function addAttributeHandles(Varien_Event_Observer $observer)
    {
        $handlePrefix = 'PRODUCT_ATTRIBUTE';

        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::registry('current_product');

        //Return if it is not product page
        if (!$product instanceof Mage_Catalog_Model_Product) {
            return;
        }

        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();

        foreach ($product->getAttributes() as $attribute) {
            /** @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if ($attribute->getIsVisibleOnFront()
                && $attribute->getFrontendInput() == 'select'
            ) {
                $value = $attribute->getName() . '_' . $attribute->getFrontend()
                        ->getValue($product);
                $update->addHandle($handlePrefix . '_'
                    . $this->_prepareHandle($value));
            }
        }
    }

    /**
     * Adds CMS page path when viewing a CMS page
     *
     * @event controller_action_layout_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCmsPageHandle(Varien_Event_Observer $observer)
    {
        $handlePrefix = 'CMS_PAGE';

        $pageId = Mage::app()->getRequest()->getParam('page_id');

        //Return if we are not viewing a page
        if (!$pageId) {
            return;
        }

        /* @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();

        $identifier = Mage::getBlockSingleton('cms/page')->getPage()
            ->getIdentifier();

        $path = array_reverse(explode('/', $identifier));
        $parent = '';
        foreach ($path as $seg) {
            $elem = array_pop($path);
            $handle = $handlePrefix;
            $parent .= '_' . $this->_prepareHandle($elem);
            $handle .= $parent;

            $after = count($path);
            for ($i = 0; $i < $after; $i++) {
                $handle .= '_child';
            }

            $update->addHandle($handle);
        }
    }

    /**
     * Adds layout handle for Customer Group
     *
     * @event controller_action_layout_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCustomerGroupHandle(Varien_Event_Observer $observer)
    {
        $handlePrefix = 'CUSTOMER_GROUP';

        if (!Mage::helper('customer')->isLoggedIn()) {
            return;
        }

        /** @var $update Mage_Core_Model_Layout_Update */
        $update = $observer->getEvent()->getLayout()->getUpdate();

        $groupId = Mage::helper('customer')->getCustomer()->getGroupId();
        $groupName = Mage::getModel('customer/group')->load($groupId)
            ->getCode();
        $groupName = $this->_prepareHandle($groupName);

        $update->addHandle($handlePrefix . '_' . $groupName);
    }

    /**
     * Prepare the handle, replace dashes '-' and spaces ' ' for underscores '_'
     *
     * @param $handle
     *
     * @return mixed
     */
    protected function _prepareHandle($handle)
    {
        $handle = Mage::getModel('catalog/product')->formatUrlKey($handle);
        if (strpos($handle, '-') !== false || strpos($handle, ' ') !== false) {
            $handle = str_replace(array('-', ' '), '_', $handle);
        }

        return $handle;
    }
}
