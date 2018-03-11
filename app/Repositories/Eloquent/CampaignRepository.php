<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\CampaignRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Campaign;

class CampaignRepository implements CampaignRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Campaign Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Campaign $model)
    {
        $this->model = $model;
    }

    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }

    public function store(array $inputs)
    {
        return $this->model->create($inputs);
    }

    public function updateOrCreateOnProjectAndCode(array $inputs, string $project, string $code)
    {
        return $this->model->updateOrCreate(["project" => $project,"code" => $code],$inputs);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByProjectAndCode($project,$code)
    {
        return $this->model->project($project)->code($code)->firstOrFail();
    }

    public function update($id, array $inputs)
    {
        $this->getById($id)->update($inputs);
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
    }
}