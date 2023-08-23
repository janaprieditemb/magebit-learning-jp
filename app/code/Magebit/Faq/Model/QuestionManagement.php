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

use Magebit\Faq\Api\QuestionManagementInterface;
use Magebit\Faq\Model\Question;
use Magebit\Faq\Model\QuestionRepository;
use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Question management implementation
 */
class QuestionManagement implements QuestionManagementInterface
{
    /**
     * Question enabled status value
     */
    const STATUS_ENABLED = 1;

    /**
     * Question disabled status value
     */
    const STATUS_DISABLED = 0;

    /**
     * @param QuestionRepository $questionRepository
     */
    public function __construct(
        private readonly QuestionRepository $questionRepository
    )
    {
    }

    /**
     * Enable question by id
     *
     * @param $questionId
     * @return void
     * @throws AlreadyExistsException
     */
    public function enableQuestion($questionId): void
    {
        /** @var Question $question */
        $question = $this->questionRepository->getById($questionId);

        if ($question->getStatus() !== self::STATUS_ENABLED)
        {
            $question->setStatus(self::STATUS_ENABLED);
        }

        $this->questionRepository->save($question);
    }

    /**
     * Disable question by id
     *
     * @param $questionId
     * @return void
     * @throws AlreadyExistsException
     */
    public function disableQuestion($questionId): void
    {
        /** @var Question $question */
        $question = $this->questionRepository->getById($questionId);

        if ($question->getStatus() !== self::STATUS_DISABLED)
        {
            $question->setStatus(self::STATUS_DISABLED);
        }

        $this->questionRepository->save($question);
    }
}
