<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml;

/**
 * Items controller
 */
abstract class Brand extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * UrlRewriteFactory
     *
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    protected $storeManager;

    protected $customerSesion;

    protected $sessionModel;

    protected $brandFactory;

    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSesion customerSesion
     * @param \Magento\Backend\Model\Session $sessionModel
     * @param \Kuechenpate\Brands\Model\BrandFactory $brandFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSesion,
        \Magento\Backend\Model\Session $sessionModel,
        \Kuechenpate\Brands\Model\BrandFactory $brandFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
        $this->customerSesion = $customerSesion;
        $this->sessionModel = $sessionModel;
        $this->brandFactory = $brandFactory;
    }

    /**
     * Initiate action
     *
     * @return this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('kuechenpate::brands')->_addBreadcrumb(__('Brands'), __('Brands'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kuechenpate_Brands::brand');
    }

    /**
     * Get store identifier
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
