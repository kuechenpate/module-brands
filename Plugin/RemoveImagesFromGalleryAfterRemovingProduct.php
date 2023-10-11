<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Kuechenpate\Brands\Plugin;

use Kuechenpate\Brands\Api\Data\BrandInterface;
use Kuechenpate\Brands\Api\BrandRepositoryInterface;
use Kuechenpate\Brands\Model\Brand\Gallery\ReadHandler;
use Kuechenpate\Brands\Model\ResourceModel\Brand\Gallery;

/**
 * Responsible for deleting images from media gallery after deleting product
 */
class RemoveImagesFromGalleryAfterRemovingProduct
{
    /**
     * @var Gallery
     */
    private $galleryResource;

    /**
     * @var ReadHandler
     */
    private $mediaGalleryReadHandler;

    /**
     * @param Gallery $galleryResource
     * @param ReadHandler $mediaGalleryReadHandler
     */
    public function __construct(Gallery $galleryResource, ReadHandler $mediaGalleryReadHandler)
    {
        $this->galleryResource = $galleryResource;
        $this->mediaGalleryReadHandler = $mediaGalleryReadHandler;
    }

    /**
     * Delete media gallery after deleting product
     *
     * @param ProductRepositoryInterface $subject
     * @param callable $proceed
     * @param BrandInterface $brand
     * @return bool
     */
    public function aroundDelete(
        ProductRepositoryInterface $subject,
        callable $proceed,
        BrandInterface $brand
    ): bool {
        $mediaGalleryAttributeId = $this->mediaGalleryReadHandler->getAttribute()->getAttributeId();
        $mediaGallery = $this->galleryResource->loadProductGalleryByAttributeId($brand, $mediaGalleryAttributeId);

        $result = $proceed($brand);

        if ($mediaGallery) {
            $this->galleryResource->deleteGallery(array_column($mediaGallery, 'value_id'));
        }

        return $result;
    }
}
