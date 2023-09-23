<?php
namespace Kuechenpate\Brands\Model\Config\Source;

use Kuechenpate\Brands\Model\BrandFactory;
use Kuechenpate\Brands\Model\ResourceModel\Brand\CollectionFactory;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $brandFactory;

    protected $collectionFactory;

    /**
     * ImageUploader constructor
     *
     * @param BrandFactory $brandFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        BrandFactory $brandFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->brandFactory = $brandFactory;
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * to option array
     *
     * @return array
     */
    public function getAllOptions()
    {
        $brands = $this->collectionFactory->create()->load();
        $options = array();
        $options[] =[
            'value' => '',
            'label' => __('-- Please Select --')
        ];
        foreach($brands as $_brand){
            $brandData = $this->brandFactory->create();
            $brandData->load($_brand->getEntityId());
            $options[] =  [
                'value' => $brandData->getId(),
                'label' => $brandData->getName()
            ];
        }
        return $options;

    }

    public function toOptionArray()
    {
        $brands = $this->collectionFactory->create()->load();
        //$_objManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$brandData = $_objManager->create('Awa\Brand\Model\Brand')->getCollection();
        $options = array();
        $options[] =[
            'value' => '',
            'label' => __('-- Please Select --')
        ];
        foreach($brands as $_brand){
            $brandData = $this->brandFactory->create();
            $brandData->load($_brand->getEntityId());
            $options[] =  [
                'value' => $brandData->getId(),
                'label' => $brandData->getName()
            ];
        }
        return $options;

    }

    public function getOptionValue($brandId){
        $brandData = $this->brandFactory->create();
        $brandData->load($brandId);
        if($brandData->getName() != ''){
            return $brandData->getName();
        }
        return '';
    }
}