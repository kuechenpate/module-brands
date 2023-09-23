<?php

namespace Kuechenpate\Brands\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Kuechenpate\Brands\Model\ResourceModel\Brand\Collection;

class MassDelete extends Action
{
    protected $filter;
    protected $brandCollection;
    public function __construct(
        Context $context,
        Filter $filter,
        Collection $brandCollection
    )
    {
        $this->filter = $filter;
        $this->brandCollection = $brandCollection;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->brandCollection);
        $collectionSize = $collection->getSize();
        $collection->walk('delete');
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}