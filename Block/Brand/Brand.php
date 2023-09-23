<?php

namespace Kuechenpate\Brands\Block\Brand;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Kuechenpate\Brands\Model\BrandFactory;

class Brand extends \Magento\Framework\View\Element\Template
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
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current brand model object
     *
     * @return \Kuechenpate\Brands\Model\Brand
     */
    public function getBrand()
    {
        if (!$this->hasData('current_brand')) {
            if(!$this->_coreRegistry->registry('current_brand')){
                $brand = $this->brandFactory->create()->load($this->getProduct()->getBrand());
                $this->setData('current_brand', $brand);
            }else {
                $this->setData('current_brand', $this->_coreRegistry->registry('current_brand'));
            }
        }
        return $this->getData('current_brand');
    }

    public function getLogoUrl(){
        $brand = $this->getBrand();
        if($brand->getLogo() != ''){
            return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $brand->getLogo();
        }else{
            return '';
        }
    }

    public function getBannerUrl(){
        $brand = $this->getBrand();
        if($brand->getBanner() != ''){
            return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $brand->getBanner();
        }else{
            return '';
        }
    }

    public function getProduct(){
        if (!$this->hasData('current_product')) {
            $this->setData('current_product', $this->_coreRegistry->registry('current_product'));
        }
        return $this->getData('current_product');
    }
}