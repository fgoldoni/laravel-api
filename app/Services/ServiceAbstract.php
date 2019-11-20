<?php

namespace App\Services;

use App\Repositories\RepositoryAbstract;
use Exception;

/**
 * Class ServiceAbstract.
 */
abstract class ServiceAbstract
{
    protected $repository;

    /**
     * @return mixed
     */
    abstract protected function repository();

    protected function resolveRepository()
    {
        $repository = app()->make($this->repository());

        if (!$repository instanceof RepositoryAbstract) {
            throw new Exception("Class {$this->repository()} must be an instance of App\\Repositories\\RepositoryAbstract");
        }

        return $repository;
    }
}
