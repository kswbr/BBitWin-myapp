<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\VoteRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Vote;
use App\Repositories\Eloquent\Models\Vote\Count;
use Carbon\Carbon;

class VoteRepository implements VoteRepositoryInterface, BaseRepositoryInterface
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
    public function __construct(Vote $model)
    {
        $this->model = $model;
    }

    public function getModelName()
    {
        return gettype($this->model);
    }

    public function getProjectQuery($project)
    {
        return $this->model->project($project);
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

    public function getByProjectAndCode($project,$code)
    {
        return $this->model->project($project)->code($code)->firstOrFail();
    }

    public function getParsedChoiceList($project, $code)
    {
        $vote = $this->getByProjectAndCode($project, $code);
        $list = array_filter(explode("\n",$vote->choice));
        $parsed_list = [];

        foreach($list as $key => $val){
            $parsed_list[trim(explode(",", $val)[0])] = trim(explode(",",$val)[1]);
        }
        return $parsed_list;
    }

    public function getChoiceCount($project, $code, $choice)
    {
        $vote = $this->getByProjectAndCode($project, $code);
        return $vote->counts()->choice($choice)->count();
    }

    public function getCounts($project, $code)
    {
        $vote = $this->getByProjectAndCode($project, $code);
        return $vote->counts()->select(["id","choice","created_at"])->get();
    }

    public function choice($project, $code, $choice)
    {
        $parsed_list = $this->getParsedChoiceList($project, $code);

        if (!isset($parsed_list[$choice])) {
          return null;
        }

        $vote = $this->getByProjectAndCode($project, $code);
        return $vote->counts()->create(["choice" => $choice]);
    }


    public function getState($project, $code)
    {
        $vote = $this->getByProjectAndCode($project, $code);

        if ($vote->active === false){
            return config("contents.vote.state.inactive");
        }

        if (Carbon::now() < $vote->start_date){
            return config("contents.vote.state.stand_by");
        }

        if (Carbon::now() > $vote->end_date){
            return config("contents.vote.state.finish");
        }

        return config("contents.vote.state.active");
    }

}
