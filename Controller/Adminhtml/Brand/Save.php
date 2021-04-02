<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;
use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Kuechenpate\Brands\Controller\Adminhtml\Brand
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->brandFactory->create();
                $data = $this->getRequest()->getPostValue();
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical(print_r($data,true));
                $images = array('image', 'alternative_image', 'banner_image');
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('entity_id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getEntityId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                foreach ($images as $value) {
                    try {
                        $uploader = $this->uploaderFactory->create(['fileId' => $value]);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        // /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                        //$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(true);
                        /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                        $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                            ->getDirectoryRead(DirectoryList::MEDIA);
                        $result = $uploader->save($mediaDirectory->getAbsolutePath('brands/' . $value));
                        if ($result['error'] == 0) {
                            $data[$value] = 'brands/' . $value . $result['file'];
                        }
                    } catch (\Exception $e) {
                        //$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                        //unset($data['image']);
                    }

                    if (isset($data[$value]['delete']) && $data[$value]['delete'] == '1')
                        $data[$value] = '';
                    if (isset($data[$value]['value']) && strlen($data[$value]['value']) > 1)
                        $data[$value] = $data[$value]['value'];
                }
                $model->setData($data);

                $session = $this->sessionModel;
                $session->setPageData($model->getData());
                $model->save();

                $urlRewriteModel = $this->_objectManager->get('\Magento\UrlRewrite\Model\UrlRewriteFactory')->create();
                //$this->urlRewriteFactory->create();
                $urlRewriteData = $urlRewriteModel->getCollection()
                    ->addFieldToFilter(
                        'target_path',
                        'brands/index/index/entity_id/' . $model->getEntityId()
                    );

                foreach ($urlRewriteData->getItems() as $rewrite) {
                    $this->deleteItem($rewrite);
                }
                $urlKey = $model->getUrlKey();
                if ($model->getUseAlternative() == true) {
                    $urlKey = $model->getAlternativeUrlKey();
                }
                //$urlKey = $urlKey;
                //$this->_objectManager->get('Psr\Log\LoggerInterface')->debug('URL-KEY-BRAND: ' . $urlKey);
                if (!empty($urlKey)) {
                    if ($data['store_id'] == 0) {
                        $storeManagerDataList = $this->storeManager->getStores();
                        foreach ($storeManagerDataList as $key => $value) {

                            $urlRewriteModel = $this->urlRewriteFactory->create();
                            /* set current store id */
                            $urlRewriteModel->setStoreId($key);
                            /* this url is not created by system so set as 0 */
                            $urlRewriteModel->setIsSystem(0);
                            /* unique identifier - set random unique value to id path */
                            $urlRewriteModel->setIdPath(rand(1, 100000));

                            $urlRewriteModel->setTargetPath('brands/index/index/entity_id/' . $model->getEntityId());
                            /* set requested path which you want to create */
                            $urlRewriteModel->setRequestPath($urlKey);
                            /* save URL rewrite rule */
                            $urlRewriteModel->save();
                        }
                    } else {
                        foreach ($data['store_id'] as $storeId) {
                            $urlRewriteModel = $this->urlRewriteFactory->create();
                            /* set current store id */
                            $urlRewriteModel->setStoreId($storeId);
                            /* this url is not created by system so set as 0 */
                            $urlRewriteModel->setIsSystem(0);
                            /* unique identifier - set random unique value to id path */
                            $urlRewriteModel->setIdPath(rand(1, 100000));

                            $urlRewriteModel->setTargetPath('brands/index/index/entity_id/' . $model->getEntityId());
                            /* set requested path which you want to create */
                            $urlRewriteModel->setRequestPath($urlKey);
                            /* save URL rewrite rule */
                            $urlRewriteModel->save();
                        }
                    }
                }
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('kuechenpate_brands/*/edit', ['entity_id' => $model->getEntityId()]);
                    return;
                }
                $this->_redirect('kuechenpate_brands/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('entity_id');
                if (!empty($id)) {
                    $this->_redirect('kuechenpate_brands/*/edit', ['entity_id' => $id]);
                } else {
                    $this->_redirect('kuechenpate_brands/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('kuechenpate_brands/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
                return;
            }

            $this->_redirect('kuechenpate_brands/*/');
        }
    }

    /**
     * Delete record of given item
     *
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $item item
     *
     * @return void
     */
    public function deleteItem($item)
    {
        $item->delete();
    }
}
