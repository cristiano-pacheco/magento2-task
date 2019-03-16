<?php

namespace ITfy\Task\Model;

class Status
{
    /**
     * @var array
     */
    protected $status = [
        '1' => 'TODO',
        '2' => 'IN PROGRESS',
        '3' => 'DONE'
    ];

    /**
     * @param int|string $id
     * @return string|null
     */
    public function getStatus($id)
    {
        return $this->status[$id] ?? null;
    }
}
