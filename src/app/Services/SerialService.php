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

     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        $serial = $this->repository->getByIdWithNumbers($id);
        return $serial;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        return $this->repository->update($id,[
            "total" => $inputs["total"]
        ]);
    }


    public function create($total, $campaign)
    {
        return $this->repository->store([
            "total" => $total,
            "campaign_code" => $campaign->code
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

    public function getByCampaignWithNumbers($campaign)
    {
        return $this->repository->getByCampaignWithNumbers($campaign->code);
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

        return $number;
    }


}
