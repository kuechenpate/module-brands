<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;
use Kuechenpate\Brands\Model\Brand;

class NewAction extends \Kuechenpate\Brands\Controller\Adminhtml\Brand
{
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $attrOptionCollectionFactory;

    protected  $registry;

    public function execute()
    {
        $item = $this->brandFactory->create();
        try{
            $item->save();
        }
        catch(\Exception $e){
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

        $this->messageManager->addSuccess(__('New Brand created'));
        $this->_redirect('kuechenpate_brands/*/edit/entity_id/'.$item->getEntityId());
    }
}
