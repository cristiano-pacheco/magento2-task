<?php

namespace ITfy\Task\Model;

use Magento\Framework\Model\AbstractModel;

class Task extends AbstractModel
{
    public function _construct()
    {
        $this->_init('ITfy\Task\Model\ResourceModel\Task');
    }
}
