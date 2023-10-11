<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kuechenpate\Brands\Controller\Adminhtml\Brand\Gallery;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;

/**
 * The product gallery upload controller
 */
class Upload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Kuechenpate_Brands::brand';

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var array
     */
    private $allowedMimeTypes = [
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp'
    ];

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    private $adapterFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Kuchenpate\Brands\Model\Brand\Media\Config
     */
    private $brandMediaConfig;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Kuchenpate\Brands\Model\Brand\Media\Config $brandMediaConfig
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory = null,
        \Magento\Framework\Filesystem $filesystem = null,
        \Kuchenpate\Brands\Model\Brand\Media\Config $brandMediaConfig = null
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->adapterFactory = $adapterFactory ?: ObjectManager::getInstance()
            ->get(\Magento\Framework\Image\AdapterFactory::class);
        $this->filesystem = $filesystem ?: ObjectManager::getInstance()
            ->get(\Magento\Framework\Filesystem::class);
        $this->brandMediaConfig = $brandMediaConfig ?: ObjectManager::getInstance()
            ->get(\Kuchenpate\Brands\Model\Brand\Media\Config::class);
    }

    /**
     * Upload image(s) to the product gallery.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        try {
            $uploader = $this->_objectManager->create(
                \Magento\MediaStorage\Model\File\Uploader::class,
                ['fileId' => 'image']
            );
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $imageAdapter = $this->adapterFactory->create();
            $uploader->addValidateCallback('brands_brand_image', $imageAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $result = $uploader->save(
                $mediaDirectory->getAbsolutePath($this->brandMediaConfig->getBaseTmpMediaPath())
            );
            $this->_eventManager->dispatch(
                'brands_brand_gallery_upload_image_after',
                ['result' => $result, 'action' => $this]
            );

            if (is_array($result)) {
                unset($result['tmp_name']);
                unset($result['path']);

                $result['url'] = $this->brandMediaConfig->getTmpMediaUrl($result['file']);
                $result['file'] = $result['file'] . '.tmp';
            } else {
                $result = ['error' => 'Something went wrong while saving the file(s).'];
            }
        } catch (LocalizedException $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        } catch (\Throwable $e) {
            $result = ['error' => 'Something went wrong while saving the file(s).', 'errorcode' => 0];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));
        return $response;
    }

    /**
     * Get the set of allowed file extensions.
     *
     * @return array
     */
    private function getAllowedExtensions()
    {
        return array_keys($this->allowedMimeTypes);
    }
}
