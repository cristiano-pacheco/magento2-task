<?php

namespace ITfy\Task\Model;

class Status
{
    const STATUS_TODO = '1';
    const STATUS_IN_PROGRESS = '2';
    const STATUS_DONE = '3';

    /**
     * @var array
     */
    protected $status = [
        self::STATUS_TODO => 'TODO',
        self::STATUS_IN_PROGRESS => 'IN PROGRESS',
        self::STATUS_DONE => 'DONE'
    ];

    /**
     * @param int|string $id
     * @return string|null
     */
    public function getStatus($id)
    {
        return $this->status[$id] ?? null;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->status;
    }
}
