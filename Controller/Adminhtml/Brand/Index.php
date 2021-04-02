<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

class Index extends \Kuechenpate\Brands\Controller\Adminhtml\Brand
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('kuechenpate::brands');
        $resultPage->getConfig()->getTitle()->prepend(__('Kuechenpate Brands'));
        $resultPage->addBreadcrumb(__('Kuechenpate'), __('Kuechenpate'));
        $resultPage->addBreadcrumb(__('Brands'), __('Brands'));
        return $resultPage;
    }
}
