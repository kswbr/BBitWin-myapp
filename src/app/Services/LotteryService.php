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

    public function getInCampaign($campaign)
    {
        return $this->repository->getCampaignQuery($campaign->code)->get();
    }

     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        $lottery = $this->repository->getById($id);
        foreach(config("contents.entry.state") as $state_label => $state) {
            $lottery->{$state_label} = $this->getStateCount($lottery,$state_label);
        }
        return $lottery;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function getFirstInCampaign($campaign)
    {
        return $this->repository->getFirstInCampaign($campaign->code);
    }

    public function getByCode($code)
    {
        return $this->repository->getByCode($code);
    }

    public function getByCodeForWinner($code)
    {
        $lottery = $this->repository->getByCode($code);
        if (!$lottery) {
          return false;
        }
        return [
          "name" => $lottery->name,
          "code" => $lottery->code,
        ];
    }


    public function update($id, array $inputs)
    {
        if (isset($inputs["start_date"])) {
            $inputs["start_date"] = new Carbon($inputs["start_date"]);
        }
        if (isset($inputs["end_date"])) {
            $inputs["end_date"] = new Carbon($inputs["end_date"]);
        }
        unset($inputs["campaign_code"]);
        unset($inputs["code"]);
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
    public function create($name, $rate, $total, $limit, $active,$code, $start_date, $end_date, $daily_increment, $daily_increment_time, $order, $campaign)
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
            "daily_increment" => $daily_increment,
            "daily_increment_time" => $daily_increment_time,
            "active" => $active,
            "order" => $order
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

    public function getRemainingOfCompleted($lottery)
    {
        return $this->repository->getRemainingOfCompleted($lottery->code);
    }

    public function getStateCount($lottery, $state_label)
    {
        return $this->repository->getStateCount($lottery->code,$state_label);
    }


}
