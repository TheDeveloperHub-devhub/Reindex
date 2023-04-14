<?php

declare(strict_types=1);

namespace DeveloperHub\Reindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Indexer\ActionFactory;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Indexer\StateInterface;
use Magento\Indexer\Model\ResourceModel\Indexer\State;

class MassReset extends AbstractReindex
{
    /**
     * @var State
     */
    protected $stateResourceModel;

    /**
     * Construct
     *
     * @param Context $context
     * @param IndexerRegistry $registry
     * @param ActionFactory $actionFactory
     * @param State $stateResourceModel
     */
    public function __construct(
        Context $context,
        IndexerRegistry $registry,
        ActionFactory $actionFactory,
        State $stateResourceModel
    ) {
        parent::__construct(
            $context,
            $registry,
            $actionFactory
        );
        $this->stateResourceModel = $stateResourceModel;
    }

    /**
     * @param array $indexerIds
     * @return void
     */
    protected function reindex(array $indexerIds): void
    {
        try {
            $startTime = microtime(true);
            $connection = $this->stateResourceModel->getConnection();
            $connection->update(
                $connection->getTableName('indexer_state'),
                ['status' => StateInterface::STATUS_INVALID],
                ['indexer_id in (?)' => $indexerIds]
            );

            $time = (int)microtime(true) - $startTime;

            $this->messageManager->addSuccessMessage(
                implode(',', $indexerIds) .
                ' indexer reset successfully ' . gmdate('H:i:s', (int)$time)
            );
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __("We couldn't invalidate indexer(s) because of an error.")
            );
        }
    }
}
