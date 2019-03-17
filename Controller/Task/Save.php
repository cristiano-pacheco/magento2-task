<?php

namespace ITfy\Task\Controller\Task;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Zend\Validator\Date as DateValidator;
use ITfy\Task\Model\TaskFactory;
use ITfy\Task\Model\ResourceModel\Task as TaskResourceModel;
use ITfy\Task\Model\Status;


class Save extends Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var DateValidator
     */
    protected $dateValidator;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var TaskFactory
     */
    protected $taskModelFactory;

    /**
     * @var TaskResourceModel
     */
    protected $taskResourceModel;

    /**
     * @var Status
     */
    protected $statusModel;

    /**
     * Save constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param TimezoneInterface $timezone
     * @param DateValidator $dateValidator
     * @param TaskFactory $taskModelFactory
     * @param TaskResourceModel $taskResourceModel
     * @param Status $statusModel
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        TimezoneInterface $timezone,
        DateValidator $dateValidator,
        DataPersistorInterface $dataPersistor,
        TaskFactory $taskModelFactory,
        TaskResourceModel $taskResourceModel,
        Status $statusModel
    ) {
        $this->logger = $logger;
        $this->timezone = $timezone;
        $this->taskModelFactory = $taskModelFactory;
        $this->dateValidator = $dateValidator;
        $this->dataPersistor = $dataPersistor;
        $this->taskResourceModel = $taskResourceModel;
        $this->statusModel = $statusModel;
        parent::__construct($context);
    }


    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('customer/task');
        }

        try {
            $this->validateForm();

            $data = $this->getRequest()->getParams();
            if (isset($data['id']) && !empty($data['id'])) {
                $this->updateTask($data);
            } else {
                $this->createTask($data);
            }

            $this->messageManager->addSuccessMessage(__('Task saved successfully.'));

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('customer_task', $this->getRequest()->getParams());
            return $this->resultRedirectFactory->create()->setPath('*/*/new');
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later')
            );
            $this->dataPersistor->set('customer_task', $this->getRequest()->getParams());
        }

        return $this->resultRedirectFactory->create()->setPath('customer/task');
    }


    protected function updateTask(array $data)
    {
        $taskModel = $this->taskModelFactory->create();
        $this->taskResourceModel->load($taskModel, (int)$data['id']);

        $taskModel->setName($data['name'])
            ->setDescription($data['description'])
            ->setAssignedPerson($data['assigned_person'])
            ->setStartedAt($this->convertDataToDb($data['started_at']))
            ->setFinishedAt($this->convertDataToDb($data['finished_at']))
            ->setStatus($data['status']);

        $this->taskResourceModel->save($taskModel);
    }

    /**
     * @param array $data
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    protected function createTask(array $data)
    {
        $taskModel = $this->taskModelFactory->create();

        $taskModel->setData([
            'name' => $data['name'],
            'description' => trim($data['description']),
            'assigned_person' => $data['assigned_person'],
            'started_at' => $this->convertDataToDb($data['started_at']),
            'finished_at' => $this->convertDataToDb($data['finished_at']),
            'status' => $data['status']
        ]);

        $this->taskResourceModel->save($taskModel);
    }

    protected function convertDataToDb($date)
    {
        if (!$date) {
            return null;
        }

        try {
            $dateTime = $this->timezone
                ->date(new \DateTime($date))
                ->format('Y-m-d H:i:s');

            return $dateTime;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function validateForm()
    {
        $request = $this->getRequest();

        if (trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the name value and try again'));
        }

        if (strlen(trim($request->getParam('name'))) > 100) {
            throw new LocalizedException(__('The name field can not contain more than 100 characters'));
        }

        if (trim($request->getParam('description')) === '') {
            throw new LocalizedException(__('Enter the description value and try again'));
        }

        if (trim($request->getParam('status')) === '') {
            throw new LocalizedException(__('Enter the status value and try again'));
        }

        if ($this->isInvalidStatusValue($request->getParam('status'))) {
            throw new LocalizedException(__('The value of status is invalid'));
        }

        $startedAt = $request->getParam('started_at');
        $finishedAt = $request->getParam('finished_at');

        if ($startedAt && !$this->isValidDate($startedAt)) {
            throw new LocalizedException(__('Invalid started at value'));
        }

        if ($finishedAt && !$this->isValidDate($finishedAt)) {
            throw new LocalizedException(__('Invalid finished at value'));
        }

        if ($finishedAt && !$startedAt) {
            throw new LocalizedException(__('Enter the started at value and try again'));
        }

        if ($this->isStartDateLessThanEndDate($startedAt, $finishedAt)) {
            throw new LocalizedException(__('The started at value must be less than the finished at'));
        }

        return $request->getParams();
    }

    /**
     * @param int|string $status
     * @return bool
     */
    protected function isInvalidStatusValue($status)
    {
        return !in_array($status, array_keys($this->statusModel->getStatuses()));
    }

    /**
     * @param string $date
     * @return bool
     */
    protected function isValidDate($date)
    {
        if (!$date) {
            return false;
        }

        try {
            $dateTime = $this->timezone
                ->date(new \DateTime($date))
                ->format('Y-m-d');
            return $this->dateValidator->isValid($dateTime);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    protected function isStartDateLessThanEndDate($startDate, $endDate)
    {
        return $startDate && $endDate && strtotime($startDate) > strtotime($endDate);
    }
}
