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
        $model->create($inputs);
        return $model;
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

    public function performInCampaign($campaign_code)
    {
        $result = ["is_winner" => false, "winning_lottery" => null,"losed_lotteries" => []];
        $lotteries = $this->model->campaign($campaign_code)->active()->inSession()->get();

        $to_draw = function($lottery) {
            $base = 10000;
            $rand = mt_rand(0, 100 * $base);
            return $rand < ($lottery->rate * $base);
        };

        foreach($lotteries as $lottery){
            if ($to_draw($lottery) && $this->getRemaining($lottery->code) > 0){
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

    public function getRemaining($lottery_code)
    {
        $state = config("contents.entry.state");
        $lottery = $this->model->code($lottery_code)->first();
        $win_count = $lottery->entries()->state($state["win"])->count();
        $win_special_count = $lottery->entries()->state($state["win_special"])->count();
        $win_completed_count = $lottery->entries()->state($state["win_posting_completed"])->count();
        $ret = $lottery->limit - ($win_count + $win_special_count + $win_completed_count);
        ($ret < 0) and $ret = 0;
        return $ret;
    }

    public function getRemainingOfCompleted($lottery_code)
    {
        $state = config("contents.entry.state");
        $lottery = $this->model->code($lottery_code)->first();
        $win_completed_count = $lottery->entries()->state($state["win_posting_completed"])->count();
        return $lottery->limit - $win_completed_count;
    }

    public function getState($lottery_code)
    {
        $lottery = $this->model->code($lottery_code)->first();

        if ($lottery->active === false){
            return config("contents.lottery.state.inactive");
        }

        if (Carbon::now() < $lottery->start_date){
            return config("contents.lottery.state.stand_by");
        }

        if (Carbon::now() > $lottery->end_date){
            return config("contents.lottery.state.finish");
        }

        if ($this->getRemaining($lottery) <= 0){
            return config("contents.lottery.state.full_entry");
        }

        return config("contents.lottery.state.active");
    }


}
