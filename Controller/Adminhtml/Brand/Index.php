<?php

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kuechenpate_Brands::brand');
    }
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Kuechenpate_Brands::brand');
        $resultPage->getConfig()->getTitle()->prepend(__('Brands'));
        return $resultPage;
    }
}