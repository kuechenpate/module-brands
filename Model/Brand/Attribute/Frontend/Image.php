<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product image attribute frontend
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Kuechenpate\Brands\Model\Brand\Attribute\Frontend;

use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Image extends AbstractFrontend
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * Returns url to product image
     *
     * @param  \Kuechenpate\Brands\Model\Brand $brand
     *
     * @return string|false
     */
    public function getUrl($brand)
    {
        $image = $brand->getData($this->getAttribute()->getAttributeCode());
        $url = false;
        if (!empty($image)) {
            $url = $this->_storeManager
                    ->getStore($brand->getStore())
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'brands/' . ltrim($image, '/');
        }
        return $url;
    }
}
