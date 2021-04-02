<?php

namespace Kuechenpate\Brands\Model;

class NameUsage implements \Magento\Framework\Option\ArrayInterface
{
    /**#@+
     * Status values
     */
    const BRAND_USE_ALTERNATIVE_NAME = 1;

    const BRAND_USE_ORIGINAL_NAME = 0;

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [self::BRAND_USE_ALTERNATIVE_NAME => __('use alternative'), self::BRAND_USE_ORIGINAL_NAME => __('use original')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    public function toOptionArray()
    {
        return $this->getOptionArray();
    }
}
