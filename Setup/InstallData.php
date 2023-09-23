<?php

namespace Kuechenpate\Brands\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Kuechenpate\Brands\Setup\BrandSetupFactory;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{
    protected $brandSetupFactory;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    public function __construct(
        BrandSetupFactory $brandSetupFactory,
        EavSetupFactory $eavSetupFactory
    ){
        $this->brandSetupFactory = $brandSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $brandSetup = $this->brandSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $brandSetup->installEntities();
        $entities = $brandSetup->getDefaultEntities();
        foreach ($entities as $entityName => $entity) {
            $brandSetup->addEntityType($entityName, $entity);
        }
        $setup->endSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand',
            [
                'group' => 'General',
                'type' => 'int',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => 'Kuechenpate\Brands\Model\Brand',
                'label' => 'Brand',
                'input' => 'multiselect',
                'class' => '',
                'source' => 'Kuechenpate\Brands\Model\Config\Source\Options',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '1',
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
    }
}