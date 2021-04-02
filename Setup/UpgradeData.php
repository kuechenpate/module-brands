<?php

namespace Kuechenpate\Brands\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    private $brandSetupFactory;

    public function __construct(
        \Kuechenpate\Brands\Setup\BrandSetupFactory $brandSetupFactory
    ) {
        $this->brandSetupFactory = $brandSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {

        }
    }
}
