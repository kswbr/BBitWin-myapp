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

    public function create($project, $provider,  $provider_id, $etc_data, $user, $type = 1)
    {
        return $this->repository->store([
            "provider_id" => $provider_id,
            "provider" => $provider,
            "project" => $project,
            "etc_data" => $etc_data,
            "user_id" => $user->id,
            "type" => $type,
        ]);
    }

    public function findByPlayerInfo($project, $provider,  $provider_id, $player_type = false)
    {
        return $this->repository->findByPlayerInfo($project, $provider,  $provider_id, $player_type);
    }

    public function getCampaignCount($player, $campaign)
    {
        return $this->repository->getCampaignCount($player->id, $campaign->code);
    }

    public function checkInCampaignCount($player, $campaign)
    {
        $check_date = (string) new Carbon();
        return $this->repository->checkInCampaignCount($player->id, $campaign->code, $check_date);
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
