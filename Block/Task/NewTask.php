<?php

namespace ITfy\Task\Block\Task;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use ITfy\Task\Block\Task\Traits\TaskBlock;
use ITfy\Task\Model\Status;

class NewTask extends Template
{
    use TaskBlock;

    /**
     * @var Status
     */
    protected $statusModel;

    public function __construct(
        Context $context,
        Status $statusModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->statusModel = $statusModel;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('New Ticket'));
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statusModel->getStatuses();
    }
}
