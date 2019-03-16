<?php

namespace ITfy\Task\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Task extends AbstractDb
{
    public function _construct()
    {
        $this->_init('itfy_task_entity', 'entity_id');
    }
}
