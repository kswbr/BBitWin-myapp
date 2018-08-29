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
        $query = $this->repository->getAdminInProjectQuery($project);
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

    public function update($id, array $inputs, $allow_admin_data = true)
    {
        unset($inputs["password"]);
        if (!$allow_admin_data) {
            unset($inputs["allow_campaign"]);
            unset($inputs["allow_vote"]);
            unset($inputs["allow_user"]);
            unset($inputs["allow_over_project"]);
            unset($inputs["allow_serial_campaign"]);
            unset($inputs["role"]);
        }
        $this->repository->update($id,$inputs);
    }

    public function changePassword($id, $password)
    {
        $this->repository->update($id,["password" => bcrypt($password)]);
    }


    public function createAdmin($name, $email, $password, $project, $role, $allow_over_project, $allow_campaign, $allow_vote, $allow_user , $allow_serial_campaign)
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
            "allow_serial_campaign" => $allow_serial_campaign
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
