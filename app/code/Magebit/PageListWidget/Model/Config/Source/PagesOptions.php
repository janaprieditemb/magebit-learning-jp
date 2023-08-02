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

namespace Magebit\PageListWidget\Model\Config\Source;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Widget pages options entity model
 *
 * @api
 * @since 1.0.0
 */
class PagesOptions implements OptionSourceInterface {
    /**
     * @var PageRepositoryInterface
     */
    private PageRepositoryInterface $pageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $seachCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->pageRepository = $pageRepository;
        $this->seachCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Creates option array suitable for widget
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $optionArray = [];

        $pages = $this->getPagesList();

        if(count($pages) > 0) {
            foreach ($pages as $index=>$page) {
                $optionArray[$index]['value'] = $page->getIdentifier();
                $optionArray[$index]['label'] = $page->getTitle();
            }
        }

        return $optionArray;
    }

    /**
     * Returns list of CMS pages
     *
     * @return PageInterface[]
     */
    public function getPagesList(): array
    {
        $searchCriteria = $this->seachCriteriaBuilder->create();

        $pageCollection = [];

        try {
            $pageCollection = $this->pageRepository->getList($searchCriteria)->getItems();
        } catch (LocalizedException $exception) {
            $this->logger->critical($exception);
        }

        return $pageCollection;
    }
}
