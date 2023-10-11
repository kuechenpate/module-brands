<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kuechenpate\Brands\Model\ResourceModel\Brand;

use Kuechenpate\Brands\Model\ResourceModel\Brand\Gallery;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Query\Generator;
use Magento\Framework\DB\Select;
use Magento\Framework\App\ResourceConnection;

/**
 * Class for retrieval of all product images
 */
class Image
{
    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var Generator
     */
    private $batchQueryGenerator;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @param Generator $generator
     * @param ResourceConnection $resourceConnection
     * @param int $batchSize
     */
    public function __construct(
        Generator $generator,
        ResourceConnection $resourceConnection,
        $batchSize = 100
    ) {
        $this->batchQueryGenerator = $generator;
        $this->resourceConnection = $resourceConnection;
        $this->connection = $this->resourceConnection->getConnection();
        $this->batchSize = $batchSize;
    }

    /**
     * Get all brand images.
     *
     * @return \Generator
     */
    public function getAllBrandImages(): \Generator
    {
        $batchSelectIterator = $this->batchQueryGenerator->generate(
            'value_id',
            $this->getVisibleImagesSelect(),
            $this->batchSize,
            \Magento\Framework\DB\Query\BatchIteratorInterface::NON_UNIQUE_FIELD_ITERATOR
        );

        foreach ($batchSelectIterator as $select) {
            foreach ($this->connection->fetchAll($select) as $key => $value) {
                yield $key => $value;
            }
        }
    }

    /**
     * Get used brand images.
     *
     * @return \Generator
     */
    public function getUsedBrandImages(): \Generator
    {
        $batchSelectIterator = $this->batchQueryGenerator->generate(
            'value_id',
            $this->getUsedImagesSelect(),
            $this->batchSize,
            \Magento\Framework\DB\Query\BatchIteratorInterface::NON_UNIQUE_FIELD_ITERATOR
        );

        foreach ($batchSelectIterator as $select) {
            foreach ($this->connection->fetchAll($select) as $key => $value) {
                yield $key => $value;
            }
        }
    }

    /**
     * Get the number of unique images of brands.
     *
     * @return int
     */
    public function getCountAllBrandImages(): int
    {
        $select = $this->getVisibleImagesSelect()
            ->reset('columns')
            ->reset('distinct')
            ->columns(
                new \Zend_Db_Expr('count(distinct value)')
            );

        return (int) $this->connection->fetchOne($select);
    }

    /**
     * Get the number of unique and used images of brands.
     *
     * @return int
     */
    public function getCountUsedBrandImages(): int
    {
        $select = $this->getUsedImagesSelect()
            ->reset('columns')
            ->reset('distinct')
            ->columns(
                new \Zend_Db_Expr('count(distinct value)')
            );

        return (int) $this->connection->fetchOne($select);
    }

    /**
     * Return select to fetch all brands images.
     *
     * @return Select
     */
    private function getVisibleImagesSelect(): Select
    {
        return $this->connection->select()->distinct()
            ->from(
                ['images' => $this->resourceConnection->getTableName(Gallery::GALLERY_TABLE)],
                'value as filepath'
            )->where(
                'disabled = 0'
            );
    }

    /**
     * Return select to fetch all used brand images.
     *
     * @return Select
     */
    private function getUsedImagesSelect(): Select
    {
        return $this->connection->select()->distinct()
            ->from(
                ['images' => $this->resourceConnection->getTableName(Gallery::GALLERY_TABLE)],
                'value as filepath'
            )->joinInner(
                ['image_value' => $this->resourceConnection->getTableName(Gallery::GALLERY_VALUE_TABLE)],
                'images.value_id = image_value.value_id',
                []
            )->where(
                'images.disabled = 0 AND image_value.disabled = 0'
            );
    }
}
