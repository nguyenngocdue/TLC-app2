<?php

namespace App\Services;

use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Log;

class BaseService implements BaseServiceInterface
{
    public $repository;
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function all($request)
    {
        return $this->repository->all($request);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }
    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
