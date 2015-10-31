<?php

class Bennoislost_ExtraLayoutHandles_Model_Layout_Handle_Category
    extends Bennoislost_ExtraLayoutHandles_Model_Layout_Handle_AbstractHandle
{
    /**
     * @var Mage_Catalog_Model_Category
     */
    private $category;

    public function __construct(
        Mage_Catalog_Model_Category $category,
        Mage_Core_Model_Layout $layout
    ) {
        $this->category = $category;
        $this->layout = $layout;

        $this->getLayoutHandles();
    }

    private function getLayoutHandles()
    {
        $handlePrefix = 'CATEGORY';

        //if we are taking a look at a category page we don't add handles
        if (in_array('catalog_product_view',
            $this->layout->getUpdate()->getHandles())) {
            $handlePrefix = 'PRODUCT_CATEGORY';
        }

        //We add the category paths here, ex: CATEGORY_123 and CATEGORY_12_child
        $path = array_reverse(
            array_slice(
                $this->category->getPathIds(), 1, -1)
        );

        foreach ($path as $id) {
            $elem = array_pop($path);
            $count = count($path);
            $handle = $handlePrefix . '_' . $elem;
            for ($i = 0; $i <= $count; $i++) {
                $handle .= '_child';
            }

            $this->addHandle($handle);
            $this->addHandle(
                substr($handle, 0, strrpos($handle, 'child'))
                . $this->category->getUrlKey());
        }
    }
}
