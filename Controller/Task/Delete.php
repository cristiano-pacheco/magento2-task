<?php

namespace ITfy\Task\Controller\Task;

use Magento\Framework\App\Action\Action;
use ITfy\Task\Model\TaskFactory;
use ITfy\Task\Model\ResourceModel\Task;

class Delete extends Action
{
    /**
     * @var \ITfy\Task\Model\TaskFactory
     */
    protected $taskFactory;

    /**
     * @var \ITfy\Task\Model\ResourceModel\Task
     */
    protected $taskResourceModel;

    /**
     * Delete constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \ITfy\Task\Model\TaskFactory $taskFactory
     * @param \ITfy\Task\Model\ResourceModel\Task $taskResourceModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        TaskFactory $taskFactory,
        Task $taskResourceModel
    ) {
        parent::__construct($context);
        $this->taskFactory = $taskFactory;
        $this->taskResourceModel = $taskResourceModel;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $taskId = (int)$this->getRequest()->getParam('id');

        if (!$taskId) {
            $this->messageManager->addErrorMessage(__('Invalid task'));
            return $resultRedirect->setPath('customer/task');
        }

        try {
            $taskModel = $this->taskFactory->create();
            $resourceModel = $this->taskResourceModel->load($taskModel, $taskId);
            $resourceModel->delete($taskModel);
            $this->messageManager->addSuccessMessage(__('You deleted the task'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while trying to delete the task'));
            return $resultRedirect->setPath('customer/task');
        }

        return $resultRedirect->setPath('customer/task');
    }
} 
