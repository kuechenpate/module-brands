<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kuechenpate\Brands\Model\Brand\Image;

use Kuechenpate\Brands\Helper\Image as ImageHelper;
use Kuechenpate\Brands\Model\Brand;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;
use Magento\Theme\Model\ResourceModel\Theme\Collection as ThemeCollection;
use Magento\Framework\App\Area;
use Magento\Framework\View\ConfigInterface;

class Cache implements ResetAfterRequestInterface
{
    /**
     * @var ConfigInterface
     */
    protected $viewConfig;

    /**
     * @var ThemeCollection
     */
    protected $themeCollection;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param ConfigInterface $viewConfig
     * @param ThemeCollection $themeCollection
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ConfigInterface $viewConfig,
        ThemeCollection $themeCollection,
        ImageHelper $imageHelper
    ) {
        $this->viewConfig = $viewConfig;
        $this->themeCollection = $themeCollection;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Retrieve view configuration data
     *
     * Collect data for 'Magento_Catalog' module from /etc/view.xml files.
     *
     * @return array
     */
    protected function getData()
    {
        if (!$this->data) {
            /** @var \Magento\Theme\Model\Theme $theme */
            foreach ($this->themeCollection->loadRegisteredThemes() as $theme) {
                $config = $this->viewConfig->getViewConfig([
                    'area' => Area::AREA_FRONTEND,
                    'themeModel' => $theme,
                ]);
                $images = $config->getMediaEntities('Kuechenpate_Brands', ImageHelper::MEDIA_TYPE_CONFIG_NODE);
                foreach ($images as $imageId => $imageData) {
                    // phpcs:ignore Magento2.Performance.ForeachArrayMerge
                    $this->data[$theme->getCode() . $imageId] = array_merge(['id' => $imageId], $imageData);
                }
            }
        }
        return $this->data;
    }

    /**
     * Resize product images and save results to image cache
     *
     * @param Brand $brand
     * @return $this
     */
    public function generate(Brand $brand)
    {
        $galleryImages = $brand->getMediaGalleryImages();
        if ($galleryImages) {
            foreach ($galleryImages as $image) {
                foreach ($this->getData() as $imageData) {
                    $this->processImageData($brand, $imageData, $image->getFile());
                }
            }
        }
        return $this;
    }

    /**
     * Process image data
     *
     * @param Brand $brand
     * @param array $imageData
     * @param string $file
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function processImageData(Brand $brand, array $imageData, $file)
    {
        $this->imageHelper->init($brand, $imageData['id'], $imageData);
        $this->imageHelper->setImageFile($file);

        if (isset($imageData['aspect_ratio'])) {
            $this->imageHelper->keepAspectRatio($imageData['aspect_ratio']);
        }
        if (isset($imageData['frame'])) {
            $this->imageHelper->keepFrame($imageData['frame']);
        }
        if (isset($imageData['transparency'])) {
            $this->imageHelper->keepTransparency($imageData['transparency']);
        }
        if (isset($imageData['constrain'])) {
            $this->imageHelper->constrainOnly($imageData['constrain']);
        }
        if (isset($imageData['background'])) {
            $this->imageHelper->backgroundColor($imageData['background']);
        }

        $this->imageHelper->save();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function _resetState(): void
    {
        $this->data = [];
    }
}
