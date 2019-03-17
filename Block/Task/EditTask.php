<?php

namespace ITfy\Task\Block\Task;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use ITfy\Task\Block\Task\Traits\TaskBlock;
use ITfy\Task\Model\Status;
use ITfy\Task\Model\TaskFactory;
use ITfy\Task\Model\ResourceModel\Task as TaskResourceModel;
use Magento\Framework\App\Request\DataPersistorInterface;

class EditTask extends Template
{
    use TaskBlock;

    /**
     * @var TaskFactory
     */
    protected $taskModelFactory;

    /**
     * @var TaskResourceModel
     */
    protected $taskResourceModel;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var Status
     */
    protected $statusModel;

    public function __construct(
        Context $context,
        Status $statusModel,
        TaskFactory $taskModelFactory,
        TaskResourceModel $taskResourceModel,
        DataPersistorInterface $dataPersistor,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->statusModel = $statusModel;
        $this->taskModelFactory = $taskModelFactory;
        $this->taskResourceModel = $taskResourceModel;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Edit Task'));
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();

        $taskId = (int)$this->getRequest()->getParam('id');

        if (!$taskId) {
            throw new NoSuchEntityException();
        }

        $taskModel = $this->taskModelFactory->create();
        $this->taskResourceModel->load($taskModel, $taskId);

        if ($taskModel->getId()) {
            $this->dataPersistor->set('customer_task_model', $taskModel->getData());
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statusModel->getStatuses();
    }

    /**
     * @return \ITfy\Task\Model\Task
     */
    public function getTask()
    {
        $taskId = (int)$this->getRequest()->getParam('id');

        $taskModel = $this->taskModelFactory->create();
        $this->taskResourceModel->load($taskModel, $taskId);

        return $taskModel;
    }
}
