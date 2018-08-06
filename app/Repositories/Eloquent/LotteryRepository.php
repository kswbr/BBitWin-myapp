<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\LotteryRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Lottery;
use Carbon\Carbon;

class LotteryRepository implements LotteryRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Lottery Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Lottery $model)
    {
        $this->model = $model;
    }

    public function getModelName()
    {
        return gettype($this->model);
    }

    public function getCampaignQuery($campaign_code)
    {
        return $this->model->campaign($campaign_code)->ordered();
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
        return $model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
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

    public function getFirstInCampaign($campaign_code)
    {
        return $this->model->campaign($campaign_code)->active()->inSession()->first();
    }

    public function performInCampaign($campaign_code)
    {
        $result = ["is_winner" => false, "winning_lottery" => null,"losed_lotteries" => [], "lotteries" => []];
        $lotteries = $this->model->ordered()->campaign($campaign_code)->active()->inSession()->get();

        foreach($lotteries as $lottery){
            $result["lotteries"][] = $lottery;
            $lottery->append("result");
            if ($lottery->result && $lottery->remaining > 0){
                $result["is_winner"] = true;
                $result["winning_lottery"] = $lottery;
                break;
            } else {
                $result["is_winner"] = false;
            }
            $result["losed_lotteries"][] = $lottery;
        }
        return $result;
    }

    public function getStateCount($lottery_code, $state_label)
    {
        $lottery = $this->model->code($lottery_code)->first();
        $state = config("contents.entry.state.$state_label");
        return $lottery->entries()->state($state)->count();
    }

    public function getByCode($lottery_code)
    {
        return $this->model->code($lottery_code)->active()->inSession()->first();
    }


    public function limitUpDaily($campaign_code)
    {
        $lotteries = $this->model
            ->campaign($campaign_code)
            ->active()
            ->inSession()
            ->checkIfSetDailyIncrement()
            ->checkIfDailyIncrementHourNow()
            ->checkIfRunTimeOlder()
            ->get();

        foreach($lotteries as $lottery) {
            $lottery->limit += (int)$lottery->daily_increment;
            if ($lottery->limit > $lottery->total) {
                $lottery->limit = $lottery->total;
            }
            $lottery->run_time = Carbon::now();
            $lottery->save();
        }
        return $lotteries;
    }


}
