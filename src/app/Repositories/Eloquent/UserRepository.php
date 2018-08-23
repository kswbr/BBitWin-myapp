<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\User;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Vote Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getModelName()
    {
        return gettype($this->model);
    }

    public function getProjectQuery($project)
    {
        return $this->model->projectMembers($project);
    }

    public function getAdminInProjectQuery($project)
    {
        return $this->model->adminMembers()->projectMembers($project);
    }

    public function getInstatWinPlayersQuery($project)
    {
        return $this->model->instantWinPlayers();
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

    public function getPlayableToken()
    {
        $this->model->append('playableToken');
        return $this->model->playableToken;
    }

    public function getRetryToken()
    {
        $this->model->append('retryToken');
        return $this->model->retryToken;
    }

    public function getPostableToken()
    {
        $this->model->append('postableToken');
        return $this->model->postableToken;
    }

    public function getThanksToken()
    {
        $this->model->append('thanksToken');
        return $this->model->thanksToken;
    }

    public function getWinnerTokenAttribute()
    {
        $this->model->append('winnerToken');
        return $this->model->winnerToken;
    }

    public function getFormTokenAttribute()
    {
        $this->model->append('formToken');
        return $this->model->formToken;
    }
}
