<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kuechenpate\Brands\Model\Config\Source\Web;

use Kuechenpate\Brands\Model\Config\BrandMediaConfig;

/**
 * Option provider for catalog media URL format system setting.
 */
class BrandMediaUrlFormat implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get a list of supported catalog media URL formats.
     *
     * @codeCoverageIgnore
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => BrandMediaConfig::IMAGE_OPTIMIZATION_PARAMETERS,
                'label' => __('Image optimization based on query parameters')
            ],
            ['value' => BrandMediaConfig::HASH, 'label' => __('Unique hash per image variant (Legacy mode)')]
        ];
    }
}