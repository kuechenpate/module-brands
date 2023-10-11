<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kuechenpate\Brands\Model\Brand\Attribute\Backend\Media;

use Kuechenpate\Brands\Api\Data\BrandAttributeMediaGalleryEntryInterface;
use Kuechenpate\Brands\Model\Brand;

/**
 * Interface EntryConverterInterface. Create Media Gallery Entry and extract Entry data
 *
 * @api
 * @since 100.0.2
 */
interface EntryConverterInterface
{
    /**
     * Return Media Gallery Entry type
     *
     * @return string
     */
    public function getMediaEntryType();

    /**
     * Create Media Gallery Entry entity from a row input data
     *
     * @param Brand $brand
     * @param array $rowData
     * @return BrandAttributeMediaGalleryEntryInterface[]
     */
    public function convertTo(Brand $brand, array $rowData);

    /**
     * Convert given Media Gallery Entry to raw data collection
     *
     * @param BrandAttributeMediaGalleryEntryInterface $entry
     * @return array
     */
    public function convertFrom(BrandAttributeMediaGalleryEntryInterface $entry);
}
