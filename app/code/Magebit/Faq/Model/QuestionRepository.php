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

namespace Magebit\Faq\Model;

use Magebit\Faq\Api\Data\QuestionInterface;
use Magebit\Faq\Api\Data\QuestionSearchResultsInterface;
use Magebit\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magebit\Faq\Model\ResourceModel\Question as QuestionResource;
use Magebit\Faq\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magebit\Faq\Api\Data\QuestionInterfaceFactory;
use Magebit\Faq\Api\Data\QuestionSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Question repository implementation
 */
class QuestionRepository implements QuestionRepositoryInterface
{
    /**
     * @param QuestionResource $questionResource
     * @param QuestionInterfaceFactory $questionFactory
     * @param QuestionSearchResultsInterfaceFactory $searchResultFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param QuestionCollectionFactory $questionCollectionFactory
     */
    public function __construct(
        private readonly QuestionResource $questionResource,
        private readonly QuestionInterfaceFactory $questionFactory,
        private readonly QuestionSearchResultsInterfaceFactory $searchResultFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly QuestionCollectionFactory $questionCollectionFactory
    )
    {
    }

    /**
     * Create or update a question.
     *
     * @param QuestionInterface $question
     * @return QuestionInterface
     * @throws AlreadyExistsException
     */
    public function save(QuestionInterface $question): QuestionInterface
    {
        $this->questionResource->save($question);

        return $question;
    }

    /**
     * Retrieve question by using Question ID
     *
     * @param int $questionId
     * @return QuestionInterface
     */
    public function getById(int $questionId): QuestionInterface
    {
        $entity = $this->questionFactory->create();
        $this->questionResource->load($entity, $questionId);
        return $entity;
    }

    /**
     * Retrieve questions matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return QuestionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): QuestionSearchResultsInterface
    {
        $collection = $this->questionCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete question
     *
     * @param QuestionInterface $question
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(QuestionInterface $question): bool
    {
        try {
            $this->questionResource->delete($question);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the question.'), $e);
        }
        return true;
    }

    /**
     * Delete question by Question ID.
     *
     * @param int $questionId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $questionId): bool
    {
        try {
            $entity = $this->getById($questionId);

            $this->questionResource->delete($entity);
        } catch (NoSuchEntityException|\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the question.'), $e);
        }

        return true;
    }
}
