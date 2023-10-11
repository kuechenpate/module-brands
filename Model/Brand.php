<?php

namespace Kuechenpate\Brands\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Kuechenpate\Brands\Api\Data\BrandInterface;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;
use Magento\Framework\UrlInterface;

class Brand extends AbstractModel implements
    IdentityInterface,
    BrandInterface,
    ResetAfterRequestInterface
{
    const CACHE_TAG = 'kuechenpate_brands_brand';
    const KEY_ENTITY_TYPE_ID = 'entity_type_id';
    const KEY_ATTR_TYPE_ID = 'attribute_set_id';
    protected $_cacheTag = 'kuechenpate_brands_brand';
    protected $_eventPrefix = 'kuechenpate_brands_brand';

    /**
     * @var string
     */
    protected $_eventObject = 'brand';

    /**
     * Media converter pool
     *
     * @var Brand\Attribute\Backend\Media\EntryConverterPool
     */
    protected $mediaGalleryEntryConverterPool;

    /**
     * @var \Kuechenpate\Brands\Model\Brand\Gallery\Processor
     * @since 101.0.0
     */
    protected $mediaGalleryProcessor;

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

    /**
     * Retrieve attributes for media gallery
     *
     * @return array
     */
    public function getMediaAttributes()
    {
        if (!$this->hasMediaAttributes()) {
            $mediaAttributes = [];
            foreach ($this->getAttributes() as $attribute) {
                if ($attribute->getFrontend()->getInputType() == 'media_image') {
                    $mediaAttributes[$attribute->getAttributeCode()] = $attribute;
                }
            }
            $this->setMediaAttributes($mediaAttributes);
        }
        return $this->getData('media_attributes');
    }

    /**
     * Retrieve assoc array that contains media attribute values of the product
     *
     * @return array
     */
    public function getMediaAttributeValues()
    {
        $mediaAttributeCodes = $this->_brandMediaConfig->getMediaAttributeCodes();
        $mediaAttributeValues = [];
        foreach ($mediaAttributeCodes as $attributeCode) {
            $mediaAttributeValues[$attributeCode] = $this->getData($attributeCode);
        }
        return $mediaAttributeValues;
    }

    /**
     * Retrieve media gallery images
     *
     * @return \Magento\Framework\Data\Collection
     */
    public function getMediaGalleryImages()
    {
        $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        if (!$this->hasData('media_gallery_images')) {
            $this->setData('media_gallery_images', $this->_collectionFactory->create());
        }
        if (!$this->getData('media_gallery_images')->count() && is_array($this->getMediaGallery('images'))) {
            $images = $this->getData('media_gallery_images');
            foreach ($this->getMediaGallery('images') as $image) {
                if (!empty($image['disabled'])
                    || !empty($image['removed'])
                    || empty($image['value_id'])
                    || $images->getItemById($image['value_id']) != null
                ) {
                    continue;
                }
                $image['url'] = $this->getMediaConfig()->getMediaUrl($image['file']);
                $image['id'] = $image['value_id'];
                $image['path'] = $directory->getAbsolutePath($this->getMediaConfig()->getMediaPath($image['file']));
                $images->addItem(new \Magento\Framework\DataObject($image));
            }
            $this->setData('media_gallery_images', $images);
        }

        return $this->getData('media_gallery_images');
    }

    /**
     * Checks whether product has Media Gallery attribute.
     *
     * @return bool
     * @since 101.0.0
     */
    public function hasGalleryAttribute()
    {
        $attributes = $this->getAttributes();

        if (!isset($attributes['media_gallery'])
            || !($attributes['media_gallery'] instanceof \Magento\Eav\Model\Entity\Attribute\AbstractAttribute)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Add image to media gallery
     *
     * @param string $file file path of image in file system
     * @param string|array $mediaAttribute code of attribute with type 'media_image',
     * leave blank if image should be only in gallery
     * @param bool $move if true, it will move source file
     * @param bool $exclude mark image as disabled in product page view
     * @return \Magento\Catalog\Model\Product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addImageToMediaGallery($file, $mediaAttribute = null, $move = false, $exclude = true)
    {
        if ($this->hasGalleryAttribute()) {
            $this->getMediaGalleryProcessor()->addImage(
                $this,
                $file,
                $mediaAttribute,
                $move,
                $exclude
            );
        }

        return $this;
    }

    /**
     * Retrieve product media config
     *
     * @return Product\Media\Config
     */
    public function getMediaConfig()
    {
        return $this->_brandMediaConfig;
    }
}