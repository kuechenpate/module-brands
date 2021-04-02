<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

class Delete extends \Kuechenpate\Brands\Controller\Adminhtml\Brand
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $brandModel = $this->brandFactory->create();
                $brandModel->load($id);

                $urlRewriteModel = $this->urlRewriteFactory->create();
                $urlRewriteData = $urlRewriteModel->getCollection()
                    ->addFieldToFilter('request_path', $brandModel->getActiveUrlKey());

                foreach ($urlRewriteData->getItems() as $rewrite) {
                    $this->deleteItem($rewrite);
                }
                $brandModel->delete();
                $this->messageManager->addSuccess(__('You deleted the brand.'));
                $this->_redirect('kuechenpate_brands/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete this brand right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('kuechenpate_brands/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a brand to delete.'));
        $this->_redirect('kuechenpate_brands/*/');
    }
}
