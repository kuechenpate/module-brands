<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kuechenpate\Brands\Model\Brand\Attribute\Backend\Media;

use Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterface;
use Kuechenpate\Brands\Model\Brand;
use Magento\Framework\Api\Data\ImageContentInterface;

/**
 * Converter for Image media gallery type
 */
class ImageEntryConverter implements EntryConverterInterface
{
    /**
     * Media Entry type code
     */
    const MEDIA_TYPE_CODE = 'image';

    /**
     * @var \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterfaceFactory
     */
    protected $mediaGalleryEntryFactory;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @param \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterfaceFactory $mediaGalleryEntryFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterfaceFactory $mediaGalleryEntryFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    ) {
        $this->mediaGalleryEntryFactory = $mediaGalleryEntryFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaEntryType()
    {
        return self::MEDIA_TYPE_CODE;
    }

    /**
     * @param Brand $brand
     * @param array $rowData
     * @return BrandAttributeMediaGalleryEntryInterface $entry
     */
    public function convertTo(Brand $brand, array $rowData)
    {
        $image = $rowData;
        $brandImages = $brand->getMediaAttributeValues();
        if (!isset($image['types'])) {
            $image['types'] = array_keys($brandImages, $image['file']);
        }
        $entry = $this->mediaGalleryEntryFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $entry,
            $image,
            \Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterface::class
        );
        if (isset($image['value_id'])) {
            $entry->setId($image['value_id']);
        }
        return $entry;
    }

    /**
     * @param BrandAttributeMediaGalleryEntryInterface $entry
     * @return array
     */
    public function convertFrom(BrandAttributeMediaGalleryEntryInterface $entry)
    {
        $entryArray = [
            'value_id' => $entry->getId(),
            'file' => $entry->getFile(),
            'label' => $entry->getLabel(),
            'position' => $entry->getPosition(),
            'disabled' => $entry->isDisabled(),
            'types' => $entry->getTypes(),
            'media_type' => $entry->getMediaType(),
            'content' => $this->convertFromMediaGalleryEntryContentInterface($entry->getContent()),
        ];
        return $entryArray;
    }

    /**
     * @param ImageContentInterface $content
     * @return array
     */
    protected function convertFromMediaGalleryEntryContentInterface(
        ImageContentInterface $content = null
    ) {
        if ($content === null) {
            return null;
        }

        return [
            'data' => [
                ImageContentInterface::BASE64_ENCODED_DATA => $content->getBase64EncodedData(),
                ImageContentInterface::TYPE => $content->getType(),
                ImageContentInterface::NAME => $content->getName(),
            ],
        ];
    }
}