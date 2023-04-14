<?php

declare(strict_types=1);

namespace DeveloperHub\Reindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Indexer\ActionFactory;
use Magento\Framework\Indexer\IndexerRegistry;

abstract class AbstractReindex extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'DeveloperHub_Reindex::reindex';

    /**
     * url redirect
     */
    public const URL_REDIRECT = 'indexer/indexer/list';

    /**
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * MassReindexData constructor.
     *
     * @param Context $context
     * @param IndexerRegistry $registry
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        Context $context,
        IndexerRegistry $registry,
        ActionFactory $actionFactory
    ) {
        parent::__construct($context);
        $this->indexerRegistry = $registry;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(static::ADMIN_RESOURCE);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');

        if (!isset($indexerIds) || !is_array($indexerIds)) {
            $this->messageManager->addErrorMessage(__("Please Select the Indexers"));
            return $this->_redirect(self::URL_REDIRECT);
        }

        //phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        set_time_limit(-1);
        $this->reindex($indexerIds);

        $this->messageManager->addSuccessMessage(
            __('%1 indexer(s) have been rebuilt successfully.', count($indexerIds))
        );
        return $this->_redirect(self::URL_REDIRECT);
    }

    /**
     * @param array $indexerIds
     * @return void
     */
    abstract protected function reindex(array $indexerIds): void;
}
