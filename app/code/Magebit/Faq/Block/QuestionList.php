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

namespace Magebit\Faq\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Template;
use Magebit\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Magebit\Faq\Api\Data\QuestionInterface;
use Psr\Log\LoggerInterface;

class QuestionList extends Template implements BlockInterface
{
    /**
     * Block template file
     */
    protected $_template = "question-list.phtml";

    /**
     * @param Context $context
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param QuestionRepositoryInterface $questionRepository
     * @param SortOrderBuilder $sortOrderBuilder
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly QuestionRepositoryInterface $questionRepository,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly LoggerInterface $logger,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve enabled questions list sorted by position
     *
     * @return array
     */
    public function getQuestionList(): array
    {
        $items = [];

        try {
            $sortOrder = $this->sortOrderBuilder->setField('position')->setDirection('desc')->create();
            $this->searchCriteriaBuilder->setSortOrders([$sortOrder]);
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(QuestionInterface::STATUS, 1)
                ->create();
            $result = $this->questionRepository->getList($searchCriteria);
            $items = $result->getItems();
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
        }

        return $items;
    }
}
