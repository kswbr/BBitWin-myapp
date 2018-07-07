<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\PlayerRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Player;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlayerRepository implements PlayerRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Player Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Player $model)
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

    public function findByPlayerInfo(string $project, string $provider, string $provider_id, $player_type = false)
    {
        $query = $this->model->project($project)->provider($provider)->providerId($provider_id);

        if ($player_type) {
            $query->type($player_type);
        }

        return $query->first();
    }

}
