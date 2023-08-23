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

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

/**
 * Delete button configuration provider
 */
class Delete implements ButtonProviderInterface
{
    private UrlInterface $urlBuilder;

    /**
     * @param Context $context
     */
    public function __construct(
        private readonly Context $context
    )
    {
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * Retrieve button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return $data = [
            'label' => __('Delete Question'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->urlBuilder->getUrl('*/*/delete', ['id' => $this->getModelId()]) . '\', {data: {}})',
            'sort_order' => 20,
        ];
    }

    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId(): ?int
    {
        return $this->context->getRequest()->getParam('id');
    }
}
