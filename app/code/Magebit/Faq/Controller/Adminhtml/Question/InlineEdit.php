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

namespace Magebit\Faq\Controller\Adminhtml\Question;

use Magebit\Faq\Api\Data\QuestionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor;
use Magebit\Faq\Api\QuestionRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magebit\Faq\Model\Question;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;

/**
 * Question grid inline edit controller
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magebit_Faq::save';

    /**
     * @param Context $context
     * @param PostDataProcessor $postDataProcessor
     * @param QuestionRepositoryInterface $questionRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        private readonly PostDataProcessor $postDataProcessor,
        private readonly QuestionRepositoryInterface $questionRepository,
        private readonly JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return ResponseInterface|Json|ResultInterface
     * @throws LocalizedException
     */
    public function execute(): Json|ResultInterface|ResponseInterface
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [__('Please correct the data sent.')],
                    'error' => true,
                ]
            );
        }

        foreach (array_keys($postItems) as $questionId) {
            /** @var Question $question */
            $question = $this->questionRepository->getById($questionId);
            try {
                $questionData = $question->getData();
                $this->validatePost($questionData, $question, $error, $messages);
                $question->setData(array_merge($questionData, $postItems[$questionId]));
                $this->questionRepository->save($question);
            } catch (LocalizedException|\RuntimeException $e) {
                $messages[] = $this->getErrorWithQuestionId($question, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithQuestionId(
                    $question,
                    __('Something went wrong while saving the page.')
                );
                $error = true;
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }

    /**
     * Post data validation
     *
     * @param array $questionData
     * @param Question $question
     * @param $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $questionData, Question $question, &$error, array &$messages): void
    {
        if (!$this->postDataProcessor->validateRequireEntry($questionData)) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithQuestionId($question, $error->getText());
            }
        }
    }

    /**
     * Error message showing particular question id
     *
     * @param QuestionInterface $question
     * @param $errorText
     * @return string
     */
    protected function getErrorWithQuestionId(QuestionInterface $question, $errorText): string
    {
        return '[Question ID: ' . $question->getId() . '] ' . $errorText;
    }
}
