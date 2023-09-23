<?php

namespace Kuechenpate\Brands\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

class BrandSetup extends EavSetup {
    const ENTITY_TYPE_CODE = 'kuechenpate_brands_brand';

    protected function getAttributes() {
        $attributes = [];
        $attributes['is_featured'] = [
            'group' => 'General',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'is featured',
            'input' => 'boolean',
            'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => '0',
            'user_defined' => false,
            'default' => '0',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'position' => '1',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['name'] = [
            'group' => 'General',
            'type' => 'varchar',
            'label' => 'Brand name',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => '1',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '10',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['logo'] = [
            'group' => 'General',
            'type' => 'varchar',
            'label' => 'Logo',
            'input' => 'image',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '20',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['description'] = [
            'group' => 'General',
            'type' => 'text',
            'label' => 'Description',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '30',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '1',
        ];
        $attributes['banner'] = [
            'group' => 'General',
            'type' => 'varchar',
            'label' => 'Banner Image',
            'input' => 'image',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '40',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['url_key'] = [
            'group' => 'Search Engine Optimization',
            'type' => 'varchar',
            'label' => 'URL Key',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'frontend_class' => 'validate-length maximum-length-255',
            'required' => '1',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '50',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['meta_title'] = [
            'group' => 'Search Engine Optimization',
            'type' => 'varchar',
            'label' => 'Meta Title',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'frontend_class' => 'validate-length maximum-length-255',
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '51',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['meta_keywords'] = [
            'group' => 'Search Engine Optimization',
            'type' => 'varchar',
            'label' => 'Meta Keywords',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'frontend_class' => 'validate-length maximum-length-255',
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '52',
            'note' => '',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        $attributes['meta_description'] = [
            'group' => 'Search Engine Optimization',
            'type' => 'varchar',
            'label' => 'Meta Description',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'frontend_class' => 'validate-length maximum-length-255',
            'required' => '0',
            'user_defined' => false,
            'default' => '',
            'unique' => false,
            'position' => '53',
            'note' => 'max. 255 chars',
            'visible' => '1',
            'wysiwyg_enabled' => '0',
        ];
        // Add your more entity attributes here...
        return $attributes;
    }

    public function getDefaultEntities() {
        $entities = [
            self::ENTITY_TYPE_CODE => [
                'entity_model' => 'Kuechenpate\Brands\Model\ResourceModel\Brand',
                'attribute_model' => 'Kuechenpate\Brands\Model\ResourceModel\Eav\Attribute',
                'table' => self::ENTITY_TYPE_CODE,
                'increment_model' => null,
                'additional_attribute_table' => self::ENTITY_TYPE_CODE . '_eav_attribute',
                'entity_attribute_collection' => 'Kuechenpate\Brands\Model\ResourceModel\Attribute\Collection',
                'attributes' => $this->getAttributes(),
            ],
        ];
        return $entities;
    }
}