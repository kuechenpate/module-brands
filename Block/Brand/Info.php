<?php

namespace Kuechenpate\Brands\Block\Brand;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Kuechenpate\Brands\Model\BrandFactory;

class Info extends Brand
{
    /**
     * Magento string lib
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Kuechenpate\Brands\Model\BrandFactory
     */
    protected $brandFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param StoreManagerInterface $storeManager
     * @param BrandFactory $brandFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\StringUtils $string,
        StoreManagerInterface $storeManager,
        BrandFactory $brandFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->string = $string;
        $this->storeManager = $storeManager;
        $this->brandFactory = $brandFactory;
        parent::__construct($context,$registry,$string,$storeManager, $brandFactory, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $brand = $this->getBrand();
        if ($brand) {
            $logoUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $brand->getLogo();
            $metaTitle = $brand->getMetaTitle();
            if ($metaTitle) {
                $this->pageConfig->getTitle()->set($metaTitle);
            }

            $metaDescription = $brand->getMetaDescription();
            if ($metaDescription) {
                $this->pageConfig->setDescription($metaDescription);
            } else {
                $this->pageConfig->setDescription($this->string->substr($brand->getDescription(), 0, 255));
            }
        }
        
        return $this;
    }
}