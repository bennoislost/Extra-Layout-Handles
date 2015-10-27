<?php

abstract class Bennoislost_ExtraLayoutHandles_Model_Layout_Handle_AbstractHandle
{
    private $layoutHandles;

    /**
     * @var Mage_Core_Model_Layout
     */
    protected $layout;

    public function addHandle($handle)
    {
        $this->layoutHandles[] = $handle;

        return $this;
    }

    public function updateLayoutHandles()
    {
        foreach($this->layoutHandles as $layoutHandle) {
            $this->layout->getUpdate()->addHandle($layoutHandle);
        }
    }
}
