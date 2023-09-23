<?php

namespace Kuechenpate\Brands\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;

class Brand extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'kuechenpate_brands_brand';
    const KEY_ENTITY_TYPE_ID = 'entity_type_id';
    const KEY_ATTR_TYPE_ID = 'attribute_set_id';
    protected $_cacheTag = 'kuechenpate_brands_brand';
    protected $_eventPrefix = 'kuechenpate_brands_brand';

    protected function _construct()
    {
        parent::_construct();

        $this->_init(\Kuechenpate\Brands\Model\ResourceModel\Brand::class);
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    public function saveCollection(array $data)
    {
        if (isset($data[$this->getId()])) {
            $this->addData($data[$this->getId()]);
            $this->getResource()->save($this);
        }
        return $this;
    }
    public function setEntityTypeId($entityTypeId)
    {
        return $this->setData(self::KEY_ENTITY_TYPE_ID, $entityTypeId);
    }
    public function getEntityTypeId()
    {
        return $this->getData(self::KEY_ENTITY_TYPE_ID);
    }
    public function setAttributeSetId($attrSetId)
    {
        return $this->setData(self::KEY_ATTR_TYPE_ID, $attrSetId);
    }
    public function getAttributeSetId()
    {
        return $this->getData(self::KEY_ATTR_TYPE_ID);
    }
    protected function _getResource()
    {
        return parent::_getResource();
    }
    /**
     * Retrieve default attribute set id
     *
     * @return int
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }
}