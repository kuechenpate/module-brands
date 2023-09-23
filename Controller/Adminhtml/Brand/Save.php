<?php

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Kuechenpate\Brands\Model\BrandFactory;
use Kuechenpate\Brands\Model\ImageUploader;

class Save extends Action
{
    protected $brandFactory;
    protected $imageUploader;

    public function __construct(
        Context $context,
        BrandFactory $brandFactory,
        ImageUploader $imageUploader
    ) {
        $this->brandFactory = $brandFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kuechenpate_Brands::brand');
    }

    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store_id');
        $data = $this->getRequest()->getParams();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $params = [];
            $brandData = $this->brandFactory->create();
            $brandData->setStoreId($storeId);
            $params['store'] = $storeId;
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            } else {
                $brandData->load($data['entity_id']);
                $params['entity_id'] = $data['entity_id'];
            }
            //logo image
            if (isset($data['logo'][0]['name']) && isset($data['logo'][0]['tmp_name'])) {
                $data['logo'] = $this->imageUploader->moveFileFromTmp($data['logo'][0]['name'],'logo');
            } elseif (isset($data['logo'][0]['name']) && !isset($data['logo'][0]['tmp_name'])) {
                $data['logo'] = $data['logo'][0]['name'];
            }

            //banner image
            if (isset($data['banner'][0]['name']) && isset($data['banner'][0]['tmp_name'])) {
                $data['banner'] = $this->imageUploader->moveFileFromTmp($data['banner'][0]['name'], 'banner');
            } elseif (isset($data['banner'][0]['name']) && !isset($data['banner'][0]['tmp_name'])) {
                $data['banner'] = $data['banner'][0]['name'];
            }

            $brandData->addData($data);
            $this->_eventManager->dispatch(
                'kuechenpate_brands_brand_prepare_save',
                ['object' => $this->brandFactory, 'request' => $this->getRequest()]
            );
            try {
                $brandData->save();
                $this->messageManager->addSuccessMessage(__('You saved this brand.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $params['entity_id'] = $brandData->getId();
                    $params['_current'] = true;
                    return $resultRedirect->setPath('*/*/edit', $params);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the brand.'));
            }
            $this->_getSession()->setFormData($this->getRequest()->getPostValue());
            return $resultRedirect->setPath('*/*/edit', $params);
        }
        return $resultRedirect->setPath('*/*/');
    }
}