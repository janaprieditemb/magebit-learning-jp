<?php
/**
 * This file is part of the Magebit package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit Faq
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2023 Magebit, Ltd. (https://magebit.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Magebit\Faq\Ui\Component\Form\Button;

use Magento\Backend\Block\Widget\Button\SplitButton;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Save button configuration provider
 */
class Save implements ButtonProviderInterface
{
    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly UrlInterface $urlBuilder
    )
    {
    }

    /**
     * Retrieve button data
     *
     * @return array
     */
    public function getButtonData() : array
    {
        return [
            'label' => __('Save'),
            'class' => 'save-split-button',
            'button_class' => '',
            'class_name' => SplitButton::class,
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'options' => [
                'save_close' => [
                    'id' => 'close',
                    'label' => __('Save & Close'),
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'save'],
                            'integration' => ['gridUrl' => $this->urlBuilder->getUrl('*/*/')],
                        ],
                        'form-role' => 'save',
                    ],
                ],
            ],
            'sort_order' => 30,
        ];
    }
}
