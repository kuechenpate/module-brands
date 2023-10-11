<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Media library image config interface
 */
namespace Kuechenpate\Brands\Model\Brand\Media;

/**
 * Interface \Kuechenpate\Brands\Model\Brand\Media\ConfigInterface
 *
 * @api
 */
interface ConfigInterface
{
    /**
     * Retrieve base url for media files
     *
     * @return string
     */
    public function getBaseMediaUrl();

    /**
     * Retrieve base path for media files
     *
     * @return string
     */
    public function getBaseMediaPath();

    /**
     * Retrieve url for media file
     *
     * @param string $file
     * @return string
     */
    public function getMediaUrl($file);

    /**
     * Retrieve file system path for media file
     *
     * @param string $file
     * @return string
     */
    public function getMediaPath($file);
}
