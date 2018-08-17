<?php

namespace App\Services;

use App\Repositories\VoteRepositoryInterface;
use Carbon\Carbon;

class VoteService
{
    protected $repository;

    public function __construct(
        VoteRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getPageInProject($page,$project)
    {
        $query = $this->repository->getProjectQuery($project);
        return $this->repository->getPaginate($page,$query);
    }

    public function getFirstInProject($project)
    {
        return $this->repository->getFirstInProject($project);
    }

    public function getDataSet($project, $vote)
    {
        $data = $this->repository->getCounts($project, $vote->code);
        $choice_list = $this->repository->getParsedChoiceList($project, $vote->code);

        $groupByChoice = $data->groupBy(function($item, $key){
            return $item["choice"];
        });

        $grouped = [];
        foreach($groupByChoice as $key => $group) {
            $grouped[$choice_list[$key]] = $group->groupBy(function($item, $key) {
                return Carbon::parse($item["created_at"])->format("Y-m-d-h");
            })->toArray();
        }

        return $grouped;
    }

    public function getChoiceCount($project, $vote, $choice)
    {
        return $this->repository->getChoiceCount($project, $vote->code, $choice);
    }

    public function choice($project, $vote, $choice)
    {
        return $this->repository->choice($project, $vote->code, $choice);
    }


    /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        unset($inputs["code"]);

        if (isset($inputs["start_date"])) {
            $inputs["start_date"] = new Carbon($inputs["start_date"]);
        }
        if (isset($inputs["end_date"])) {
            $inputs["end_date"] = new Carbon($inputs["end_date"]);
        }

        $this->repository->update($id,$inputs);
    }


    /**
     * Save the model from the database.
     *
     * @param string $name
     * @param string $code
     * @param string $choice
     * @param bool $active
     * @param string $start_date
     * @param string $end_date
     * @param string $project
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function create($name, $code, $choice, $active, $start_date, $end_date, $project)
    {
        return $this->repository->store([
            "name" => $name,
            "code" => $code,
            "choice" => $choice,
            "active" => $active,
            "start_date" => new Carbon($start_date),
            "end_date" => new Carbon($end_date),
            "project" => $project
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


}
