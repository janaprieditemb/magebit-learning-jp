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

namespace Magebit\Faq\Api;

/**
 * Interface for managing questions.
 * @api
 * @since 1.0.0
 */
interface QuestionManagementInterface
{
    /**
     * Enable question by id
     *
     * @param $questionId
     * @return void
     */
    public function enableQuestion($questionId): void;

    /**
     * Disable question by id
     *
     * @param $questionId
     * @return void
     */
    public function disableQuestion($questionId): void;
}
