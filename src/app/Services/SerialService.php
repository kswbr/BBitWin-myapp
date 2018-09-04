<?php

namespace App\Services;

use App\Repositories\SerialRepositoryInterface;
use Carbon\Carbon;

class SerialService
{
    protected $repository;

    public function __construct(
        SerialRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function getPageInProject($page,$project)
    {
        $query = $this->repository->getProjectQuery($project);
        return $this->repository->getPaginate($page,$query);
    }

     /**
     * FindOrFail Model and return the instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        $serial = $this->repository->getById($id);
        return $serial;
    }
    public function getByIdWithNumbers($id)
    {
        $serial = $this->repository->getByIdWithNumbers($id);
        return $serial;
    }

    public function getByIdWithNumbersPDO($id)
    {
        $pdo = $this->repository->getByIdWithNumbersPDO($id);
        return $pdo;
    }



    public function getByCode($serial_code)
    {
        $serial = $this->repository->getByCode($serial_code);
        return $serial;
    }

    public function getModelName()
    {
        return $this->repository->getModelName();
    }

    public function update($id, array $inputs)
    {
        return $this->repository->update($id,[
            "name" => $inputs["name"],
            "total" => $inputs["total"],
            "winner_total" => $inputs["winner_total"],
        ]);
    }


    public function create($name, $total, $winner_total, $code, $project)
    {
        return $this->repository->store([
            "name" => $name,
            "total" => $total,
            "winner_total" => $winner_total,
            "code" => $code,
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

    public function hasNumber($serial, int $number)
    {
        return $this->repository->hasNumber($serial->code,$number);
    }

    public function connectNumbersToPlayer($serial, $player, int $number)
    {
        return $this->repository->connectNumbersToPlayerByCode($serial->code, $player->id, $number);
    }

    public function createUniqueNumber($serial)
    {
        $min = config("contents.serial.number.min");
        $max = config("contents.serial.number.max");

        $ret = false;
        do {
            $number = rand($min,$max);
            $ret = $this->repository->hasNumberByCode($serial->code,$number);
        } while($ret);

        $this->repository->createNumberByCode($serial->code, $number);

        return $number;
    }

    public function createWinnerNumber($serial)
    {
        $take_count = $serial->winner_total - $serial->winner_numbers_count;
        return $this->repository->updateRandomWinnerNumbersByCode($serial->code, $take_count);
    }



}
