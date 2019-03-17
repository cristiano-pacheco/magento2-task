<?php

namespace ITfy\Task\Block\Task\Traits;

trait TaskBlock
{
    /**
     * @param array $task
     * @return string
     */
    public function getEditUrl($task)
    {
        return $this->getUrl('customer/task/edit', ['id' => $task['entity_id']]);
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/task/index');
    }

    /**
     * @return string
     */
    public function getNewTaskUrl()
    {
        return $this->getUrl('customer/task/new');
    }

    /**
     * @return string
     */
    public function getSaveTaskUrl()
    {
        return $this->getUrl('customer/task/save');
    }

    /**
     * @param array $task
     * @return string
     */
    public function getDeleteUrl($task)
    {
        return $this->getUrl('customer/task/delete', ['id' => $task['entity_id']]);
    }

    public function formatDateToApp($date)
    {
        if (!$date) {
            return null;
        }
        return $this->formatDate($date, \IntlDateFormatter::SHORT, true);
    }
}
