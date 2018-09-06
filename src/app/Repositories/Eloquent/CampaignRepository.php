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

    public function getModelName()
    {
        return gettype($this->model);
    }

    public function getPaginate($n, $search_query = null)
    {
        if ($search_query) {
            return $search_query->paginate($n);
        } else {
            return $this->model->paginate($n);
        }
    }

    public function store(array $inputs)
    {
        $model = $this->model->newInstance();
        $model->project = $inputs["project"];
        $model->code = $inputs["code"];
        $model->name = $inputs["name"];
        $model->limited_days = $inputs["limited_days"];

        $model->save();
        return $model;
    }

    // public function updateOrCreateOnProjectAndCode(array $inputs, string $project, string $code)
    // {
    //     return $this->model->updateOrCreate(["project" => $project,"code" => $code],$inputs);
    // }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getProjectQuery($project)
    {
        return $this->model->project($project);
    }

    public function getByProjectAndCode($project,$code)
    {
        return $this->model->project($project)->code($code)->firstOrFail();
    }

    public function getFirstInProject($project)
    {
        $query = $this->getProjectQuery($project);
        return $query->first();
    }

    /**
     * Update the model in the database.
     *
     * @param $id
     * @param array $inputs
     */
    public function update($id, array $inputs)
    {
        $this->getById($id)->update($inputs);
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
    }
}
