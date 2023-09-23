<?php

namespace Kuechenpate\Brands\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Kuechenpate\Brands\Model\BrandFactory;


class Data extends AbstractHelper
{
    protected $_storeManager;
    protected $_brandFactory;
    protected $_urlBuilder;
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        BrandFactory $brandFactory
    )
    {
        $this->_storeManager = $storeManager;
        $this->_brandFactory = $brandFactory;
        $this->_urlBuilder = $context->getUrlBuilder();
        parent::__construct($context);
    }

    public function getBannerUrlById($brandId)
    {
        $brand = $this->_brandFactory->create()->load($brandId);
        if($brand->getBanner() != ''){
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $brand->getBanner();
        }else{
            return '';
        }
    }
    public function getLogoUrlById($brandId)
    {
        $brand = $this->_brandFactory->create()->load($brandId);
        if($brand->getLogo() != ''){
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $brand->getLogo();
        }else{
            return '';
        }
    }
    public function getNameById($brandId)
    {
        $brand = $this->_brandFactory->create()->load($brandId);
        return $brand->getName();
    }
    public function getLinkById($brandId)
    {
        $brand = $this->_brandFactory->create()->load($brandId);
        return $this->_urlBuilder->getDirectUrl($brand->getUrlKey());
    }
}