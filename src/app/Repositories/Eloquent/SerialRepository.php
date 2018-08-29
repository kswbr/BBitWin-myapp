<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\SerialRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Campaign\Serial;
use Carbon\Carbon;

class SerialRepository implements SerialRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Serial Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Serial $model)
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
        return $model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByIdWithNumbers($id)
    {
        return $this->model->with('numbers')->findOrFail($id);
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

    public function getByCampaign($campaign_code)
    {
        return $this->model->campaign($campaign_code)->get();
    }

    public function getNumbersCountInCampaign($campaign_code)
    {
        $serial = $this->model->campaign($campaign_code)->first();
        return $serial->numbers()->count();
    }

    public function getByCampaignWithNumbers($campaign_code)
    {
        return $this->model->campaign($campaign_code)->with('numbers')->first();
    }

    public function hasNumberInCampaign($campaign_code,$number)
    {
        return $this->model->campaign($campaign_code)->whereHas('numbers', function($query) use ($number){
            $query->number($number);
        })->count() === 1;
    }

    public function connectNumbersToPlayerInCampaign($campaign_code, $player_id, $number)
    {
        $serial = $this->model->campaign($campaign_code)->first();
        return $serial->numbers()->create(["player_id" => $player_id, "number" => $number]);
    }



}
