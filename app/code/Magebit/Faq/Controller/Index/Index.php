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

namespace Magebit\Faq\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

/**
 * Class Index
 */
class Index implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private readonly PageFactory $pageFactory
    )
    {
    }

    /**
     * Execute action
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute(): Page|ResultInterface|ResponseInterface
    {
        return $this->pageFactory->create();
    }
}
