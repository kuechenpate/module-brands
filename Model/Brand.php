<?php
namespace Kuechenpate\Brands\Model;

use Magento\Framework\Model\AbstractModel;

class Brand extends AbstractModel {
    const ENTITY = 'kuechenpate_brand';

    protected function _construct() {
        /* full resource classname */
        $this->_init('Kuechenpate\Brands\Model\ResourceModel\Brand');
    }
}
