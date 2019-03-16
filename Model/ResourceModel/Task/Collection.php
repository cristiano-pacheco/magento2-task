<?php

namespace ITfy\Task\Model\ResourceModel\Task;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init('ITfy\Task\Model\Task', 'ITfy\Task\Model\ResourceModel\Task');
    }
}
