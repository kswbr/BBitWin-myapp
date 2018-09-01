<?php

namespace App\Services;

use App\Repositories\SerialRepositoryInterface;
use Carbon\Carbon;

class SerialService
{
    protected $repository;

    public function __construct(
        SerialRepositoryInterface $repository
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
        $serial = $this->repository->getById($id);
        return $serial;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        return $this->repository->update($id,[
            "name" => $inputs["name"],
            "total" => $inputs["total"]
        ]);
    }


    public function create($name, $total, $campaign_code, $project)
    {
        return $this->repository->store([
            "name" => $name,
            "total" => $total,
            "campaign_code" => $campaign_code,
            "project" => $project,
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

    public function getByCampaign($campaign)
    {
        return $this->repository->getByCampaign($campaign->code);
    }
    public function getNumbersCountInCampaign($campaign)
    {
        return $this->repository->getNumbersCountInCampaign($campaign->code);
    }

    public function hasNumberInCampaign($campaign, int $number)
    {
        return $this->repository->hasNumberInCampaign($campaign->code,$number);
    }

    public function connectNumbersToPlayerInCampaign($campaign, $player, int $number)
    {
        return $this->repository->connectNumbersToPlayerInCampaign($campaign->code, $player->id, $number);
    }

    public function createUniqueNumberInCampaign($campaign)
    {
        $min = config("contents.serial.number.min");
        $max = config("contents.serial.number.max");

        $ret = false;
        do {
            $number = rand($min,$max);
            $ret = $this->repository->hasNumberInCampaign($campaign->code,$number);
        } while($ret);

        $this->repository->createNumberInCampaign($campaign->code, $number);

        return $number;
    }


}
