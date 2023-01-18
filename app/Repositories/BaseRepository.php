<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model= $model;
    }

    public function getAll()
    {
       return $this->model::all();
    }

    public function getById($id)
    {
        return $this->model::find($id);
    }

    public function delete($id)
    {
        return $this->model::destroy($id);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
       return $this->model->where('id',$id)->update($data);
    }
}
