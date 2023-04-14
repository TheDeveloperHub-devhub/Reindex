<?php

declare(strict_types=1);

namespace DeveloperHub\Reindex\Controller\Adminhtml\Indexer;

use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\IndexerInterface;

class MassReindexData extends AbstractReindex
{
    /**
     * @param array $indexerIds
     * @return void
     */
    protected function reindex(array $indexerIds): void
    {
        $startTime = microtime(true);
        foreach ($indexerIds as $indexerId) {
            $indexer = $this->indexerRegistry->get($indexerId);
            try {
                $indexer->reindexAll();
                $time = (int) microtime(true) - $startTime;
                $this->messageManager->addSuccessMessage(
                    $indexer->getTitle() .
                    ' indexer has rebuilt successfully ' . gmdate('H:i:s', (int) $time)
                );
            } catch (\InvalidArgumentException | \Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Because of an error we are unable to reindex %1 ', $indexer->getTitle())
                );
            }
        }
    }
    /**
     * Return indexer action instance
     *
     * @param IndexerInterface $indexer
     * @return ActionInterface
     * @throws \InvalidArgumentException
     */
    protected function getActionInstance($indexer): ActionInterface
    {
        return $this->actionFactory->create(
            $indexer->getActionClass(),
            [
                'indexStructure' => $indexer->getStructureInstance(),
                'data' => $indexer->getData(),
            ]
        );
    }
}
