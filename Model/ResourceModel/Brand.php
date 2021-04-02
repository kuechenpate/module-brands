<?php
namespace Kuechenpate\Brands\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;

/*
Our resource class extends from \Magento\Eav\Model\Entity\AbstractEntity,
and set $this->_read, $this->_write class properties  in _construct() method
*/
class Brand extends AbstractEntity {
    protected function _construct() {
        $this->_read = 'kuechenpate_brand_read';
        $this->_write = 'kuechenpate_brand_write';
    }

    public function getEntityType() {
        if(empty($this->_type)) {
            $this->setType(\Kuechenpate\Brands\Model\Brand::ENTITY);
        }
        return parent::getEntityType();
    }
}
