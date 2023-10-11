<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kuechenpate\Brands\Api\Data;

/**
 * @api
 * @since 100.0.2
 */
interface BrandInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    const CACHE_TAG = 'kuechenpate_brands_brand';
    const KEY_ENTITY_TYPE_ID = 'entity_type_id';
    const KEY_ATTR_TYPE_ID = 'attribute_set_id';

    /**#@+
     * Constants defined for keys of  data array
     */
    const IS_FEATURED = 'is_featured';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const LOGO = 'logo';

    const BANNER = 'banner';

    const MEDIA_GALLERY = 'media_gallery';

    const URL_KEY = 'url_key';

    const META_TITLE = 'meta_title';

    const META_KEYWORDS = 'meta_keywords';

    const META_DESCRIPTION = 'meta_description';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const ATTRIBUTES = [
        self::IS_FEATURED,
        self::NAME,
        self::DESCRIPTION,
        self::LOGO,
        self::BANNER,
        self::MEDIA_GALLERY,
        self::URL_KEY,
        self::META_TITLE,
        self::META_KEYWORDS,
        self::META_DESCRIPTION,
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    /**#@-*/

    /**
     * Brand id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set brand id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    public function getIdentities();

    public function setEntityTypeId($entityTypeId);

    public function getEntityTypeId();

    public function getDefaultAttributeSetId();

    /**
     * Brand name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set Brand name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Brand attribute set id
     *
     * @return int|null
     */
    public function getAttributeSetId();

    /**
     * Set Brand attribute set id
     *
     * @param int $attributeSetId
     * @return $this
     */
    public function setAttributeSetId($attributeSetId);

    /**
     * Brand created date
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set brand created date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Brand updated date
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set brand updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);


    /**
     * Get media gallery entries
     *
     * @return \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterface[]|null
     */
    public function getMediaGalleryEntries();

    /**
     * Set media gallery entries
     *
     * @param \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterface[] $mediaGalleryEntries
     * @return $this
     */
    public function setMediaGalleryEntries(array $mediaGalleryEntries = null);

}
