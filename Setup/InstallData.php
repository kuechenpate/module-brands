<?php
namespace Kuechenpate\Brands\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
    private $brandSetupFactory;

    public function __construct(
        \Kuechenpate\Brands\Setup\BrandSetupFactory $brandSetupFactory
    ) {
        $this->brandSetupFactory = $brandSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        $brandEntity = \Kuechenpate\Brands\Model\Brand::ENTITY;

        $brandSetup = $this->brandSetupFactory->create(['setup' => $setup]);

        $brandSetup->installEntities();

        /*

        Add attributes for the brand entity

        Using the addAttribute method on the instance of \Kuechenpate\Brands\Setup\BrandSetupFactory,
        we are instructing Magento to add a number of attributes to its entity.
        Within addAttribute method, there is a call to the $this->attributeMapper->map($attr, $entityTypeId) method.
        attributeMapper conforms to Magento\Eav\Model\Entity\Setup\PropertyMapperInterface, which looking at
        vendor/magento/module-eav/etc/di.xml has a preference for the Magento\Eav\Model\Entity\Setup\PropertyMapper\Composite class,
        which further initailize the following mapper classes:

        1) Magento\Eav\Model\Entity\Setup\PropertyMapper
        2) Magento\Customer\Model\ResourceModel\Setup\PropertyMapper
        3) Magento\Catalog\Model\ResourceModel\Setup\PropertyMapper
        4) Magento\ConfigurableProduct\Model\ResourceModel\Setup\PropertyMapper

        Since we are defining our own entity types, the mapper class we are mostly interested in is Magento\Eav\Model\Entity\Setup\PropertyMapper.
        The key strings match the column names in the eav_attribute table, while the value strings match the keys of our array passed to the addAttriubte
        method of within InstallData.php

        */

        $brandSetup->addAttribute(
            $brandEntity, 'image', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'url_key', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'alternative_name', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'alternative_image', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'alternative_url_key', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'use_alternative', ['type' => 'int']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'banner_image', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'description', ['type' => 'text']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'meta_title', ['type' => 'varchar']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'meta_keywords', ['type' => 'text']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'meta_description', ['type' => 'text']
        );

        $brandSetup->addAttribute(
            $brandEntity, 'position', ['type' => 'int']
        );

        $setup->endSetup();
    }
}
