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

use Magento\Framework\Api\SearchResultsInterface;
use Magebit\Faq\Api\Data\QuestionInterface;

/**
 * Interface for question search results.
 *
 * @api
 * @since 1.0.0
 */
interface QuestionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get question list.
     *
     * @return QuestionInterface[]
     */
    public function getItems();

    /**
     * Set question list.
     *
     * @param QuestionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
