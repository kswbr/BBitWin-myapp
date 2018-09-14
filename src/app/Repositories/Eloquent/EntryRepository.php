<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\EntryRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Entry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntryRepository implements EntryRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Entry Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Entry $model)
    {
        $this->model = $model;
    }

    public function getModelName()
    {
        return gettype($this->model);
    }

    public function getLotteryQuery($lottery_code)
    {
        return $this->model->lotteryCode($lottery_code);
    }

    public function getPaginate($n, $search_query = null)
    {
        if ($search_query) {
            return $search_query->orderBy("id","desc")->paginate($n);
        } else {
            return $this->model->orderBy("id","desc")->paginate($n);
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

    public function getCountOfStateInLottery($lottery_code, $state)
    {
        return $this->model->lotteryCode($lottery_code)->state($state)->count();
    }

    public function getDataInLottery($lottery_code, $state = false)
    {
        $query = $this->model->select(["player_type","state","created_at"])->lotteryCode($lottery_code);
        if ($state) {
            $query->state($state);
        }
        return $query->get();
    }

    public function updateStateWhenLimitedDaysPassed($campaign_limited_days,$lottery_code){
        $limited_time = Carbon::parse("-" . (int)$campaign_limited_days . " days");
        $entries = $this->model->lotteryCode($lottery_code)->winner(false);

        foreach($entries->passed((string)$limited_time)->get() as $entry){
            $entry->state = config("contents.entry.state.win_posting_expired");
            $entry->save();
        }

        $entries = $this->model->lotteryCode($lottery_code)->postingExpiredWinner();
        foreach($entries->notPassed($limited_time)->get() as $entry){
            $entry->state = config("contents.entry.state.win");
            $entry->save();
        }
        return true;
    }

    public function getPrevDataOfPlayerInCampaign($player_id, $player_type, $campaign_code, $campaign_limited_days)
    {
      $types = implode(",", [
        config("contents.entry.state.win_special"),
        config("contents.entry.state.win"),
        config("contents.entry.state.win_posting_completed"),
        config("contents.entry.state.win_posting_expired"),
        config("contents.entry.state.lose")]);

        return $this->model->playerId($player_id)
          ->where("player_type",$player_type)
          ->with(["lottery" => function($query) use ($campaign_code,$campaign_limited_days){
              $dt = new Carbon();
              $query->where("campaign_code",$campaign_code);
              $query->where("active",true);
              $query->where("start_date","<" ,Carbon::now());
              $query->where("end_date",">", $dt->subDays((int)$campaign_limited_days));
          }])
          ->orderByRaw(DB::raw("FIELD(state, ".$types." ) ASC"))
          ->orderBy("created_at","DESC")
          ->first();
    }

}
