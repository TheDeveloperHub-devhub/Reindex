<?php

declare(strict_types=1);

namespace DeveloperHub\Reindex\Block\Backend\Grid;

use Magento\Indexer\Block\Backend\Grid\ItemsUpdater as MagentoItemsUpdater;

class ItemsUpdater extends MagentoItemsUpdater
{
    /**
     * @param mixed $argument
     * @return mixed
     */
    public function update($argument)
    {
        $argument = parent::update($argument);

        if ($this->authorization->isAllowed('DeveloperHub_Reindex::reindex') === false) {
            unset($argument['change_mode_reindex']);
        }
        return $argument;
    }
}
