<?php

namespace App\Services;

use App\Repositories\EntryRepositoryInterface;

class EntryService
{
    protected $repository;

    public function __construct(
        EntryRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getPageInLottery($page,$lottery)
    {
        $query = $this->repository->getLotteryQuery($lottery->code);
        return $this->repository->getPaginate($page,$query);
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
        $this->repository->update($id,$inputs);
    }


    /**
     * Save the model from the database.
     *
     * @param string $name
     * @param double $rate
     * @param int $total
     * @param int $limit
     * @param string $code
     * @param string $start_date
     * @param string $end_date
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function create($player, $lottery, int $state)
    {
        return $this->repository->store([
            "player_id" => $player->id,
            "player_type" => $player->type,
            "lottery_code" => $lottery->code,
            "state" => $state,
        ]);
    }

    public function getStateData($id) {
        $entry = $this->repository->getById($id);
        return config("contents.entry.state_data." . $entry->state);
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

    public function updateState($id, string $state_label)
    {
        $state = config("contents.entry.state." . $state_label);
        if (!$state) {
            return false;
        }

        $this->repository->update($id,["state" => $state]);
        return true;
    }


}
