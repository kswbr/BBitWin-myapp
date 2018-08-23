<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;

class UserService
{
    protected $repository;

    public function __construct(
        UserRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getPageInProject($page,$project)
    {
        $query = $this->repository->getAdminInProjectQuer($project);
        return $this->repository->getPaginate($page,$query);
    }

     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        $user = $this->repository->getById($id);
        return $user;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        $this->repository->update($id,$inputs);
    }

    public function createAdmin($name, $email, $password, $project, $role, $allow_over_project, $allow_campaign, $allow_vote, $allow_user )
    {
        return $this->repository->store([
            "name" => $name,
            "email" => $email,
            "password" => bcrypt($password),
            "project" => $project,
            "role" => $role,
            "allow_over_project" => $allow_over_project,
            "allow_campaign" => $allow_campaign,
            "allow_vote" => $allow_vote,
            "allow_user" => $allow_user,
        ]);
    }

    public function createPlayer($project,$name)
    {
        return $this->repository->store([
            "name" => $name,
            "project" => $project,
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
