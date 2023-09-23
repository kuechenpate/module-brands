<?php

namespace Kuechenpate\Brands\Ui\Component\Form\Brand;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Kuechenpate\Brands\Model\ResourceModel\Brand\Collection;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends AbstractDataProvider {
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    public    $_storeManager;

    /**
     * @var FilterPool
     */
    protected $filterPool;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param string           $name             [description]
     * @param string           $primaryFieldName [description]
     * @param string           $requestFieldName [description]
     * @param Collection       $collection       [description]
     * @param FilterPool       $filterPool       [description]
     * @param RequestInterface $request          [description]
     * @param array            $meta             [description]
     * @param array            $data             [description]
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collection,
        FilterPool $filterPool,
        RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collection;
        $this->filterPool = $filterPool;
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager=$storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData() {
        if (!$this->loadedData) {
            $baseurl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $storeId = (int) $this->request->getParam('store');
            $this->collection->setStoreId($storeId)->addAttributeToSelect('*');
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $temp = $item->getData();
                if(isset($temp['logo']) && $temp['logo']):
                    $img = [];
                    $img[0]['image'] = $temp['logo'];
                    $img[0]['url'] = $baseurl . $temp['logo'];
                    $temp['logo'] = $img;
                    $data = $this->dataPersistor->get('brand');
                    if (!empty($data)) {
                        $item = $this->collection->getNewEmptyItem();
                        $item>setData($data);
                        $this->loadedData[$item->getLabelId()] = $item->getData();
                        $this->dataPersistor->clear('brand');
                    }else {
                        if($items):
                            if ($item->getData('logo') != null) {

                                $t2[$item->getEntityId()] = $temp;

                                return $t2;
                            } else {
                                return $this->loadedData;
                            }
                        endif;
                    }
                endif;
                $item->setStoreId($storeId);
                $this->loadedData[$item->getEntityId()] = $item->getData();
                break;
            }
        }
        return $this->loadedData;
    }
}