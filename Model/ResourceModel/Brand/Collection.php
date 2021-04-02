<?php
namespace Kuechenpate\Brands\Model\ResourceModel\Brand;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class Collection extends AbstractCollection {
    protected function _construct() {
        /* Full model classname, full resource classname */
        $this->_init(
            'Kuechenpate\Brands\Model\Brand',
            'Kuechenpate\Brands\Model\ResourceModel\Brand'
        );
    }
}
