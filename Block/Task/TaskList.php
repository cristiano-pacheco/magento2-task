<?php

namespace ITfy\Task\Block\Task;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use ITfy\Task\Model\ResourceModel\Task\CollectionFactory as TaskCollectionFactory;
use ITfy\Task\Block\Task\Traits\TaskBlock;
use ITfy\Task\Model\Status;

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
    protected $taskFactory;

    /**
     * @var Status
     */
    protected $statusModel;

    public function __construct(
        Context $context,
        Session $customerSession,
        TaskCollectionFactory $taskFactory,
        Status $statusModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->taskFactory = $taskFactory;
        $this->statusModel = $statusModel;
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
        $TaskCollection = $this->taskFactory->create();

        $data = $TaskCollection->getData();

        return $data;
    }

    /**
     * @param int|string $id
     * @return string|null
     */
    public function getStatus($id)
    {
        return $this->statusModel->getStatus($id);
    }
}
