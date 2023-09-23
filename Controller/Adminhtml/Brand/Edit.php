<?php

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Kuechenpate\Brands\Model\BrandFactory;

class Edit extends Action
{
    protected $_coreRegistry = null;
    protected $resultPageFactory;
    protected $brandFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        BrandFactory $brandFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->brandFactory = $brandFactory;
        parent::__construct($context);
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kuechenpate_Brands::brand');
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $brandData = $this->brandFactory->create();
        if ($id) {
            $brandData->load($id);
            if (!$brandData->getId()) {
                $this->messageManager->addErrorMessage(__('This record no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $brandData->addData($data);
        }
        $this->_coreRegistry->register('entity_id', $id);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Kuechenpate_Brands::brand');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Brand'));
        return $resultPage;
    }
}