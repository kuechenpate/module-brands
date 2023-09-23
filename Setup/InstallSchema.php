<?php

namespace Kuechenpate\Brands\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Kuechenpate\Brands\Setup\EavTablesSetupFactory;
use Kuechenpate\Brands\Setup\BrandSetup;

class InstallSchema implements InstallSchemaInterface
{
    protected $eavTablesSetupFactory;

    public function __construct(EavTablesSetupFactory $eavTablesSetupFactory)
    {
        $this->eavTablesSetupFactory = $eavTablesSetupFactory;
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = BrandSetup::ENTITY_TYPE_CODE;
        $table = $setup->getConnection()
            ->newTable($setup->getTable($tableName))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )->setComment('Entity Table');

        $table->addColumn(
            'entity_type_id',
            Table::TYPE_SMALLINT,
            null,
            [
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
            ],
            'Entity Type ID'
        )->addIndex(
            $setup->getIdxName($tableName, ['entity_type_id']),
            ['entity_type_id']
        )->addForeignKey(
            $setup->getFkName(
                BrandSetup::ENTITY_TYPE_CODE,
                'entity_type_id',
                'eav_entity_type',
                'entity_type_id'
            ),
            'entity_type_id',
            $setup->getTable('eav_entity_type'),
            'entity_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $table->addColumn(
            'attribute_set_id',
            Table::TYPE_SMALLINT,
            null,
            [
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
            ],
            'Attribute Set ID'
        )->addIndex(
            $setup->getIdxName($tableName, ['attribute_set_id']),
            ['attribute_set_id']
        )->addForeignKey(
            $setup->getFkName(
                BrandSetup::ENTITY_TYPE_CODE,
                'attribute_set_id',
                'eav_attribute_set',
                'attribute_set_id'
            ),
            'attribute_set_id',
            $setup->getTable('eav_attribute_set'),
            'attribute_set_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        // Add more static attributes here...
        $table->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        );
        $setup->getConnection()->createTable($table);
        /** @var \Kuechenpate\Brands\Setup\EavTablesSetup $eavTablesSetup */
        $eavTablesSetup = $this->eavTablesSetupFactory->create(['setup' => $setup]);
        $eavTablesSetup->createEavTables(BrandSetup::ENTITY_TYPE_CODE);
        $setup->endSetup();
    }
}