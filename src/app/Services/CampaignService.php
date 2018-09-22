<?php

namespace App\Services;

use App\Repositories\CampaignRepositoryInterface;

class CampaignService
{
    protected $repository;

    public function __construct(
        CampaignRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getPageInProject($page,$project)
    {
        $query = $this->repository->getProjectQuery($project);
        return $this->repository->getPaginate($page,$query);
    }

    public function getFirstInProject($project)
    {
        return $this->repository->getFirstInProject($project);
    }

    public function getListsHasNotSerialInProject($project)
    {
        return $this->repository->getListsHasNotSerialInProject($project);
    }

    public function getByProjectAndCode($project,$code)
    {
        return $this->repository->getByProjectAndCode($project,$code);
    }


     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        unset($inputs["project"]);
        unset($inputs["code"]);
        $this->repository->update($id,$inputs);
    }


    /**
     * Save the model from the database.
     *
     * @param string $name
     * @param string $code
     * @param int $limited_days
     * @param string $project
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function create(string $name, string $code, int $limited_days, string $project)
    {
        return $this->repository->store([
            "name" => $name,
            "code" => $code,
            "limited_days" => $limited_days,
            "project" => $project
        ]);
    }

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

}
