<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kuechenpate\Brands\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\State;
use Magento\MediaStorage\Service\ImageResize;
use Magento\MediaStorage\Service\ImageResizeScheduler;
use Kuechenpate\Brands\Model\Config\BrandMediaConfig;

/**
 * Resize product images after the product is saved
 */
class ImageResizeAfterProductSave implements ObserverInterface
{
    /**
     * @var ImageResize
     */
    private $imageResize;

    /**
     * @var State
     */
    private $state;

    /**
     * @var BrandMediaConfig
     */
    private $brandMediaConfig;

    /**
     * @var ImageResizeScheduler
     */
    private $imageResizeScheduler;

    /**
     * @var bool
     */
    private $imageResizeSchedulerFlag = false;

    /**
     * Product constructor.
     *
     * @param ImageResize $imageResize
     * @param State $state
     * @param BrandMediaConfig $brandMediaConfig
     * @param ImageResizeScheduler $imageResizeScheduler
     * @param bool $imageResizeSchedulerFlag
     */
    public function __construct(
        ImageResize $imageResize,
        State $state,
        BrandMediaConfig $brandMediaConfig,
        ImageResizeScheduler $imageResizeScheduler,
        bool $imageResizeSchedulerFlag = false
    ) {
        $this->imageResize = $imageResize;
        $this->state = $state;
        $this->brandMediaConfig = $brandMediaConfig;
        $this->imageResizeScheduler = $imageResizeScheduler;
        $this->imageResizeSchedulerFlag = $imageResizeSchedulerFlag;
    }

    /**
     * Process event on 'save_commit_after' event
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $brandMediaUrlFormat = $this->brandMediaConfig->getMediaUrlFormat();
        if ($brandMediaUrlFormat == BrandMediaConfig::IMAGE_OPTIMIZATION_PARAMETERS) {
            // Skip image resizing on the Magento side when it is offloaded to a web server or CDN
            return;
        }

        /** @var $brand \Kuechenpate\Brands\Model\Brand */
        $brand = $observer->getEvent()->getBrand();

        if ($this->state->isAreaCodeEmulated()) {
            return;
        }

        if (!(bool) $brand->getId()) {
            foreach ($brand->getMediaGalleryImages() as $image) {
                $this->resizeImage($image->getFile());
            }
        } else {
            $new = $brand->getData('media_gallery');
            $original = $brand->getOrigData('media_gallery');
            $mediaGallery = !empty($new['images']) ? array_column($new['images'], 'file') : [];
            $mediaOriginalGallery = !empty($original['images']) ? array_column($original['images'], 'file') : [];

            foreach (array_diff($mediaGallery, $mediaOriginalGallery) as $image) {
                $this->resizeImage($image);
            }
        }
    }

    /**
     * Resize image in synchronous or asynchronous way
     *
     * @param string $image
     */
    private function resizeImage(string $image): void
    {
        if ($this->imageResizeSchedulerFlag) {
            $this->imageResizeScheduler->schedule($image);
        } else {
            $this->imageResize->resizeFromImageName($image);
        }
    }
}