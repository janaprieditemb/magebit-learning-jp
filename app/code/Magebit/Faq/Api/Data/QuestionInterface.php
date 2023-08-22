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

namespace Magebit\Faq\Api\Data;

/**
 * Question entity interface for API handling.
 *
 * @api
 * @since 1.0.0
 */
interface QuestionInterface
{
    /**
     * Question fields
     */
    public const ID = 'id';
    public const QUESTION = 'question';
    public const ANSWER = 'answer';
    public const STATUS = 'status';
    public const POSITION = 'position';
    public const UPDATED_AT = 'updated_at';

    /**
     * Get question id
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set question
     *
     * @param $question
     * @return self
     */
    public function setQuestion($question): self;

    /**
     * Get question
     *
     * @return string|null
     */
    public function getQuestion(): ?string;

    /**
     * Set question answer
     *
     * @param $answer
     * @return self
     */
    public function setAnswer($answer): self;

    /**
     * Get question answer
     *
     * @return string|null
     */
    public function getAnswer(): ?string;

    /**
     * Get question status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set question status
     *
     * @param $status
     * @return self
     */
    public function setStatus($status): self;

    /**
     * Get question position
     *
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * Set question position
     *
     * @param $position
     * @return self
     */
    public function setPosition($position): self;

    /**
     * Get updated at time
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;
}
