<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */
namespace Kuechenpate\Brands\Block\Adminhtml\Brand\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('kuechenpate_brands_brand_edit_tabs');
        $this->setDestElementId('kuechenpate_brands_brand_edit_form');
        $this->setTitle(__('Brand'));
    }
}
