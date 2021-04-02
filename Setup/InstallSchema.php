<?php

namespace Kuechenpate\Brands\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface {
    /*
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return null
    */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        $brandEntity = \Kuechenpate\Brands\Model\Brand::ENTITY;

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned'=>true, 'nullable'=>false, 'primary' => true],
                'Entity Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_VARCHAR,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'length' => 256, 'comment' => 'Brand Name'],
                'Brand Name'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['unsigned'=>true, 'nullable'=>false],
                'Brand created at'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['unsigned'=>true, 'nullable'=>false],
                'Brand updated at'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity',
                    ['name'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['name'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->setComment('Kuechenpate_Brands Brand Table');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity_decimal'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity'=>true, 'nullable'=>false, 'primary'=>true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Attribute Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable'=>false, 'default'=>'0'],
                'Entity Id'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                [],
                'value'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_decimal',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_decimal',
                    ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_decimal',
                    ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_decimal',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_decimal',
                    'entity_id',
                    $brandEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($brandEntity . '_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_decimal', 'store_id', 'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Kuechenpate Brands Decimal Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity_datetime'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity'=>true, 'nullable'=>false, 'primary'=>true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Attribute Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable'=>false, 'default'=>'0'],
                'Entity Id'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'value'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_decimal',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_datetime',
                    ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_datetime',
                    ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_datetime',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_datetime',
                    'entity_id',
                    $brandEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($brandEntity . '_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_datetime', 'store_id', 'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Kuechenpate Brands Datetime Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity_text'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity'=>true, 'nullable'=>false, 'primary'=>true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Attribute Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable'=>false, 'default'=>'0'],
                'Entity Id'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'value'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_text',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_text',
                    ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_text',
                    ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_text',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_text',
                    'entity_id',
                    $brandEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($brandEntity . '_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_text', 'store_id', 'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Kuechenpate Brands text Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity_int'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity'=>true, 'nullable'=>false, 'primary'=>true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Attribute Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable'=>false, 'default'=>'0'],
                'Entity Id'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'value'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_int',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_int',
                    ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_int',
                    ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_int',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_int',
                    'entity_id',
                    $brandEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($brandEntity . '_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_int', 'store_id', 'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Kuechenpate Brands Int Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable($brandEntity . '_entity_varchar'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity'=>true, 'nullable'=>false, 'primary'=>true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Attribute Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned'=>true, 'nullable'=>false, 'default'=>'0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable'=>false, 'default'=>'0'],
                'Entity Id'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                [],
                'value'
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_varchar',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type'=>\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_varchar',
                    ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($brandEntity . '_entity_varchar',
                    ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_varchar',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_varchar',
                    'entity_id',
                    $brandEntity . '_entity',
                    'entity_id'
                ),
                'entity_id',
                $setup->getTable($brandEntity . '_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    $brandEntity . '_entity_varchar', 'store_id', 'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Kuechenpate Brands Varchar Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
