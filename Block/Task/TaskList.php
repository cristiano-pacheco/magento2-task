<?php

namespace ITfy\Task\Block\Task;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use ITfy\Task\Model\ResourceModel\Task\CollectionFactory as TaskCollectionFactory;
use ITfy\Task\Block\Task\Traits\TaskBlock;

class TaskList extends Template
{
    use TaskBlock;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var TaskCollectionFactory
     */
    protected $TaskFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        TaskCollectionFactory $TaskFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->TaskFactory = $TaskFactory;
        $this->getTasks();
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Tasks'));
    }

    public function getTasks()
    {
        $data = [];

        $TaskCollection = $this->TaskFactory->create();

        $data = $TaskCollection->getData();

        return $data;
    }
}
