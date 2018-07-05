<?php

namespace App\Services;

use App\Repositories\LotteryRepositoryInterface;

class LotteryService
{
    protected $repository;

    public function __construct(
        LotteryRepositoryInterface $repository
    ) {
        $this->repository = $repository;
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
    public function getPageInProject($page,$project)
    {
        $query = $this->repository->getProjectQuery($project);
        return $this->repository->getPaginate($page,$query);
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
    public function create(array $inputs)
    {
        return $this->repository->store($inputs);
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


    public function performInCampaign($campaign)
    {
        return $this->repository->performInCampaign($campaign->code);
    }

    public function getRemaining($lottery)
    {
        return $this->repository->getRemaining($lottery->code);
    }

    public function getRemainingOfCompleted($lottery)
    {
        return $this->repository->getRemainingOfCompleted($lottery->code);
    }

}
