<?php

namespace App\Services;

use App\Repositories\PlayerRepositoryInterface;
use Carbon\Carbon;

class PlayerService
{
    protected $repository;

    public function __construct(
        PlayerRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

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
        $player = $this->repository->getById($id);
        return $player;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        $this->repository->update($id,$inputs);
    }

    public function create($provider_id, $provider,  $project, $info, $user, $type = 1)
    {
        return $this->repository->store([
            "provider_id" => $provider_id,
            "provider" => $provider,
            "info" => $info,
            "project" => $project,
            "type" => $type,
            "user_id" => $user->id,
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
