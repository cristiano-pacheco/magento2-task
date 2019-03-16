<?php

namespace ITfy\Task\Block\Task;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use ITfy\Task\Block\Task\Traits\TaskBlock;

class NewTask extends Template
{
    use TaskBlock;

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('New Ticket'));
    }
}
