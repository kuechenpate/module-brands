<?php

namespace Kuechenpate\Brands\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Api\BookmarkManagementInterface;
use Magento\Ui\Api\BookmarkRepositoryInterface;

class Bookmark extends \Magento\Ui\Component\Bookmark
{
    /**
     * @var \Kuechenpate\Brands\Model\Brand
     */
    protected $brand;

    /**
     * [__construct description]
     * @param  ContextInterface                $context            [description]
     * @param  \Kuechenpate\Brands\Model\Brand $brand              [description]
     * @param  BookmarkRepositoryInterface     $bookmarkRepository [description]
     * @param  BookmarkManagementInterface     $bookmarkManagement [description]
     * @param  {Array}  array                  $components         [description]
     * @param  {Array}  array                  $data               [description]
     */
    public function __construct(
        ContextInterface $context,
        \Kuechenpate\Brands\Model\Brand $brand,
        BookmarkRepositoryInterface $bookmarkRepository,
        BookmarkManagementInterface $bookmarkManagement,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $bookmarkRepository, $bookmarkManagement, $components, $data);
        $this->brand = $brand;
    }

    /**
    * Register component
    *
    * @return void
    */
    public function prepare()
    {
        $namespace = $this->getContext()->getRequestParam('namespace', $this->getContext()->getNamespace());
        $config = [];
        if (!empty($namespace)) {
            $storeId = $this->getContext()->getRequestParam('store');
            if (empty($storeId)) {
                $storeId = $this->getContext()->getFilterParam('store_id');
            }
            $bookmarks = $this->bookmarkManagement->loadByNamespace($namespace);
            /** @var \Magento\Ui\Api\Data\BookmarkInterface $bookmark */
            foreach ($bookmarks->getItems() as $bookmark) {
                if ($bookmark->isCurrent()) {
                    $config['activeIndex'] = $bookmark->getIdentifier();
                }
                $config = array_merge_recursive($config, $bookmark->getConfig());
                if (!empty($storeId)) {
                    $config['current']['filters']['applied']['store_id'] = $storeId;
                }
            }
        }
        $this->setData('config', array_replace_recursive($config, $this->getConfiguration($this)));
        parent::prepare();
        $jsConfig = $this->getConfiguration($this);
        $this->getContext()->addComponentDefinition($this->getComponentName(), $jsConfig);
    }
}