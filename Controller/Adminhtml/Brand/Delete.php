<?php

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Kuechenpate\Brands\Model\BrandFactory;

class Delete extends Action
{
    protected $brandFactory;
    public function __construct(
        Context $context,
        BrandFactory $brandFactory
    ) {
        $this->brandFactory = $brandFactory;
        parent::__construct($context);
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kuechenpate_Brands::brand');
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id', null);
        try {
            $brandData = $this->brandFactory->create()->load($id);
            if ($brandData->getId()) {
                $brandData->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the brand.'));
            } else {

                $this->messageManager->addErrorMessage(__('Brand does not exist.'));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        return $resultRedirect->setPath('*/*');
    }
}