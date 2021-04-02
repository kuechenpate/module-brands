<?php
namespace Kuechenpate\Brands\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();
        $brandEntity = \Kuechenpate\Brands\Model\Brand::ENTITY;
        $brandEntityTableName = $brandEntity . '_entity';
        if (version_compare($context->getVersion(), '1.0.1', '<')) {

        }
    }
}

