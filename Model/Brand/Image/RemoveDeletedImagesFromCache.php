<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kuechenpate\Brands\Model\Brand\Image;

use Kuechenpate\Brands\Helper\Image;
use Kuechenpate\Brands\Model\Brand\Image\ConvertImageMiscParamsToReadableFormat;
use Kuechenpate\Brands\Model\Brand\Image\ParamsBuilder;
use Kuechenpate\Brands\Model\Brand\Media\Config;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\View\ConfigInterface;

/**
 * Delete image from cache
 */
class RemoveDeletedImagesFromCache
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $presentationConfig;

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $encryptor;

    /**
     * @var Config
     */
    private Config $mediaConfig;

    /**
     * @var WriteInterface
     */
    private WriteInterface $mediaDirectory;

    /**
     * @var ParamsBuilder
     */
    private ParamsBuilder $imageParamsBuilder;

    /**
     * @var ConvertImageMiscParamsToReadableFormat
     */
    private ConvertImageMiscParamsToReadableFormat $convertImageMiscParamsToReadableFormat;

    /**
     * @param ConfigInterface $presentationConfig
     * @param EncryptorInterface $encryptor
     * @param Config $mediaConfig
     * @param Filesystem $filesystem
     * @param ParamsBuilder $imageParamsBuilder
     * @param ConvertImageMiscParamsToReadableFormat $convertImageMiscParamsToReadableFormat
     */
    public function __construct(
        ConfigInterface $presentationConfig,
        EncryptorInterface $encryptor,
        Config $mediaConfig,
        Filesystem $filesystem,
        ParamsBuilder $imageParamsBuilder,
        ConvertImageMiscParamsToReadableFormat $convertImageMiscParamsToReadableFormat
    ) {
        $this->presentationConfig = $presentationConfig;
        $this->encryptor = $encryptor;
        $this->mediaConfig = $mediaConfig;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageParamsBuilder = $imageParamsBuilder;
        $this->convertImageMiscParamsToReadableFormat = $convertImageMiscParamsToReadableFormat;
    }

    /**
     * Remove deleted images from cache.
     *
     * @param array $files
     *
     * @return void
     */
    public function removeDeletedImagesFromCache(array $files): void
    {
        if (count($files) === 0) {
            return;
        }
        $images = $this->presentationConfig
            ->getViewConfig(['area' => \Magento\Framework\App\Area::AREA_FRONTEND])
            ->getMediaEntities(
                'Kuechenpate_Brands',
                Image::MEDIA_TYPE_CONFIG_NODE
            );

        $catalogPath = $this->mediaConfig->getBaseMediaPath();

        foreach ($images as $imageData) {
            $imageMiscParams = $this->imageParamsBuilder->build($imageData);

            if (isset($imageMiscParams['image_type'])) {
                unset($imageMiscParams['image_type']);
            }

            $cacheId = $this->encryptor->hash(
                implode('_', $this->convertImageMiscParamsToReadableFormat
                    ->convertImageMiscParamsToReadableFormat($imageMiscParams)),
                Encryptor::HASH_VERSION_MD5
            );

            foreach ($files as $filePath) {
                $this->mediaDirectory->delete(
                    $catalogPath . '/cache/' . $cacheId . '/' . $filePath
                );
            }
        }
    }
}
