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

namespace Magebit\Faq\Model\Question;

use Magebit\Faq\Model\Question;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magebit\Faq\Model\ResourceModel\Question\CollectionFactory;

/**
 * DataProvider for question creation form
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array|null
     */
    protected ?array $loadedData = null;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private readonly CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get question data
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Question $question */
        foreach ($items as $question) {
            $this->loadedData[$question->getId()] = $question->getData();
        }

        return $this->loadedData;
    }
}
