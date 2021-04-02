<?php
/**
 * Copyright © 2020 Merkur Möbel-Vertrieb GmbH. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace Kuechenpate\Brands\Block\Adminhtml\Brand\Edit\Tab;


use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use \Kuechenpate\Brands\Model\NameUsage;
use Magento\Eav\Model\Entity\Attribute\Config;



class Main extends Generic implements TabInterface
{

 /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;
    /**
     * @var \Kuechenpate\Brands\Model\NameUsage
     */
    protected $nameUsage;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Kuechenpate\Brands\Model\NameUsage $nameUsage
     * @param array $data
     */
    public function __construct(
        Store $systemStore,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        NameUsage $nameUsage,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->systemStore = $systemStore;
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->nameUsage = $nameUsage;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Brand Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Brand Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_kuechenpate_brands_brand');
		$isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->formFactory->create();
        $form->setHtmlIdPrefix('brand_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Brand Information')]);

        if ($model->getEntityId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $field = $fieldset->addField(
            'store_id',
            'select',
            [
                'label' => __('Store View'),
                'title' => __('Store View'),
                'name' => 'store_id',
                'value' => $model->getStoreId(),
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
//                set first argument true and second to false to add blank option which value is blank
//                set second argument true to add "All Store Views" option which value is 0
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            Element::class
        );
        $field->setRenderer($renderer);

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Brand Name'), 'title' => __('Brand Name'), 'required' => true]
        );

        $fieldset->addField(
            'url_key',
            'text',
            ['name' => 'url_key', 'label' => __('Url Key'), 'title' => __('Url Key'), 'required' => false]
        );


        $fieldset->addField(
            'image',
            'image',
            ['name' => 'image', 'label' => __('image'), 'title' => __('image'), 'required' => false ,  'disabled' => $isElementDisabled]
        );

        $fieldset->addField(
            'alternative_name',
            'text',
            ['name' => 'alternative_name', 'label' => __('Alternative Brand Name'), 'title' => __('Alternative Brand Name'), 'required' => false]
        );

        $fieldset->addField(
            'alternative_url_key',
            'text',
            ['name' => 'alternative_url_key', 'label' => __('Alternative Url Key'), 'title' => __('Alternative Url Key'), 'required' => false]
        );

        $fieldset->addField(
            'alternative_image',
            'image',
            ['name' => 'alternative_image', 'label' => __('Alternative Image'), 'title' => __('Alternative Image'), 'required' => false ,  'disabled' => $isElementDisabled]
        );

        $fieldset->addField(
            'use_alternative',
            'select',
            [
                'label' => __('Use Alternative'),
                'title' => __('Use Alternative'),
                'name' => 'use_alternative',
                'required' => true,
                'options' => $this->nameUsage->getOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description')
            ]
        );
        $fieldset->addField(
            'meta_title',
            'text',
            [
                'name' => 'meta_title',
                'label' => __('SEO Meta Title'),
                'title' => __('SEO Meta Title')
            ]
        );

        $fieldset->addField(
            'meta_keywords',
            'text',
            [
                'name' => 'meta_keywords',
                'label' => __('SEO Meta Keywords'),
                'title' => __('SEO Meta Keywords')
            ]
        );

        $fieldset->addField(
            'meta_description',
            'text',
            [
                'name' => 'meta_description',
                'label' => __('SEO Meta Description'),
                'title' => __('SEO Meta Description')
            ]
        );
        $fieldset->addField(
            'banner_image',
            'image',
            ['name' => 'banner_image', 'label' => __('Banner Image'), 'title' => __('Banner Image'), 'required' => false ,  'disabled' => $isElementDisabled]
        );

        $fieldset->addField(
            'position',
            'text',
            ['name' => 'position', 'label' => __('Position'), 'title' => __('Position'), 'required' => false]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
