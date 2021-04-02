<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

class Edit extends \Kuechenpate\Brands\Controller\Adminhtml\Brand
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->brandFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('kuechenpate_brands/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->sessionModel->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->coreRegistry->register('current_kuechenpate_brands_brand', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('brands_brand_edit');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('kuechenpate::brands');
        $this->_view->renderLayout();
    }
}
