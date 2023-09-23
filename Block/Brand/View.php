<?php

namespace Kuechenpate\Brands\Block\Brand;

use Magento\Store\Model\StoreManagerInterface;
use Kuechenpate\Brands\Model\BrandFactory;

class View extends Brand
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