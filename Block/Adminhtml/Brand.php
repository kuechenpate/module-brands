<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */
namespace Kuechenpate\Brands\Block\Adminhtml;

class Brand extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'brand';
        $this->_headerText = __('Brands');
        $this->_addButtonLabel = __('Add Brand');
        parent::_construct();
    }
}
