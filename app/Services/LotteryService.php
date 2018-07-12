<?php

namespace App\Services;

use App\Repositories\LotteryRepositoryInterface;
use Carbon\Carbon;

class LotteryService
{
    protected $repository;

    public function __construct(
        LotteryRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getPageInCampaign($page,$campaign)
    {
        $query = $this->repository->getCampaignQuery($campaign->code);
        return $this->repository->getPaginate($page,$query);
    }

     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        $lottery = $this->repository->getById($id);
        $lottery->remaining = $this->repository->getRemaining($lottery->code);
        foreach(config("contents.entry.state") as $state_label => $state) {
            $lottery->{$state_label} = $this->getStateCount($lottery,$state_label);
        }
        return $lottery;
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
     * @param double $rate
     * @param int $total
     * @param int $limit
     * @param string $code
     * @param string $start_date
     * @param string $end_date
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function create($name, $rate, $total, $limit, $code, $start_date, $end_date, $campaign)
    {
        return $this->repository->store([
            "name" => $name,
            "rate" => $rate,
            "total" => $total,
            "limit" => $limit,
            "code" => $code,
            "start_date" => new Carbon($start_date),
            "end_date" => new Carbon($end_date),
            "campaign_code" => $campaign->code,
            "active" => true,
            "order" => 0
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

    public function getStateCount($lottery, $state_label)
    {
        return $this->repository->getStateCount($lottery->code,$state_label);
    }


}
