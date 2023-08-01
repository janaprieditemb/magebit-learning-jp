<?php
/**
 * This file is part of the Magebit package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit Faq
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2022 Magebit, Ltd. (https://magebit.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magebit\PageListWidget\Model\Config\Source\PagesOptions;
use Magento\Cms\Model\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Widget\Block\BlockInterface;
use Magento\Cms\Model\Page;
use Psr\Log\LoggerInterface;

/**
 * Block responsible for displaying list of CMS pages as a widget
 */
class PageList extends Template implements BlockInterface {

    /**
     * Block template file
     */
    protected $_template = "page-list.phtml";

    /**
     * Display cms pages mode - all pages
     */
    const DISPLAY_MODE_ALL_PAGES = 'all_pages';

    /**
     * Display cms pages mode - specific pages
     */
    const DISPLAY_MODE_SPECIFIC_PAGES = 'specific_pages';

    /**
     * @var PagesOptions
     */
    private $pagesOptions;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        PagesOptions $pagesOptions,
        PageFactory $pageFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->pagesOptions = $pagesOptions;
        $this->pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
    }

    /**
     * Retrieve widget title
     *
     * @return string
     */
    public function getTitle(): string
    {
        $title = '';
        if ($this->hasData('title')) {
            $this->getData('title');
        }
        return $title;
    }

    /**
     * Retrieve widget display mode
     *
     * @return array|mixed|null
     */
    public function getDisplayMode()
    {
        if (!$this->hasData('display_mode')) {
            $this->setData('display_mode', self::DISPLAY_MODE_ALL_PAGES);
        }
        return $this->getData('display_mode');
    }

    /**
     * Get array of prepared CMS pages for view
     *
     * @return \Magento\Cms\Api\Data\PageInterface[]|string[]
     */
    public function getPagesList(): array
    {
        $finalCollection = [];

        switch ($this->getDisplayMode()) {
            case self::DISPLAY_MODE_SPECIFIC_PAGES:
                $pageIdentifiers = explode(',', $this->getData('selected_pages'));

                $index = 0;
                foreach ($pageIdentifiers as $pageIdentifier) {
                    $page = $this->getPage($pageIdentifier);

                    $finalCollection[$index]['title'] = $page->getTitle();
                    $finalCollection[$index]['url'] = $this->getPageUrl($page);

                    $index++;
                }

                break;
            default:
                $pageCollection = $this->pagesOptions->getPagesList();

                foreach ($pageCollection as $index=>$page) {
                    $finalCollection[$index]['title'] = $page->getTitle();
                    $finalCollection[$index]['url'] = $this->getPageUrl($page);
                }

                break;
        }

        return $finalCollection;
    }

    /**
     * Get page entity by identifier
     *
     * @param $identifier
     *
     * @return Page|null
     */
    public function getPage($identifier): ?Page
    {
        $page = $this->pageFactory->create();
        if ($identifier !== null) {
            try {
                $page->setStoreId($this->storeManager->getStore()->getId());
                $page->load($identifier,'identifier');
            } catch (NoSuchEntityException $exception) {
                $this->logger->critical($exception);
            }
        }

        if (!$page->getId()) {
            return null;
        }

        return $page;
    }

    /**
     * Retrieve page url
     *
     * @param $page
     *
     * @return string
     */
    public function getPageUrl($page): string
    {
        return $this->urlBuilder->getUrl(null, ['_direct' => $page->getIdentifier()]);
    }
}

