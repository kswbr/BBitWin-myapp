<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\SerialRepositoryInterface;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\Models\Serial;
use App\Repositories\Eloquent\Models\Serial\Number;
use Carbon\Carbon;

class SerialRepository implements SerialRepositoryInterface, BaseRepositoryInterface
{
    /**
     * Parent Serial Repository
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;
    protected $numbers_model;

    /**
     * Injection Model
     *
     * @return void;
     */
    public function __construct(Serial $model, Number $numbers_model)
    {
        $this->model = $model;
        $this->numbers_model = $numbers_model;
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
            return $search_query->numbersCount()->paginate($n);
        } else {
            return $this->model->numbersCount()->paginate($n);
        }
    }


    public function store(array $inputs)
    {
        $model = $this->model->newInstance();
        return $model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->numbersCount()->findOrFail($id);
    }

    public function getByIdWithNumbers($id)
    {
        $data = $this->model->numbersCount()->findOrFail($id);
        // $data->numbers = \DB::table('serial_numbers')->select(["is_winner","player_id","number"])->where('serial_code',$data->code)->get();

        $numbers = [];
        $dbh = \DB::connection()->getPdo();

        $stmt = $dbh->prepare("select id,is_winner,player_id,number from serial_numbers where serial_code = :serial_code");
        $code = (string)$data->code ;
        $stmt->bindParam(':serial_code', $code);
        $stmt->execute();

        foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row)
        {
            $numbers[] = $row;
        }
        $data->numbers = $numbers;

        return $data;
    }

    public function getByIdWithNumbersPDO($id)
    {
        $data = $this->model->numbersCount()->findOrFail($id);
        $numbers = [];
        $dbh = \DB::connection()->getPdo();
        $stmt = $dbh->prepare("select number,is_winner,player_id from serial_numbers where serial_code = :serial_code");
        $code = (string)$data->code ;
        $stmt->bindParam(':serial_code', $code);
        $stmt->execute();
        return $stmt;
    }


    public function getByCode($code)
    {
        return $this->model->code($code)->numbersCount()->first();
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

    public function hasNumberByCode($code,$number)
    {
        return $this->model->code($code)->whereHas('numbers', function($query) use ($number){
            $query->number($number);
        })->count() === 1;
    }

    public function createNumberByCode($code,$number)
    {
        return $this->model->code($code)->first()->numbers()->create(['number' => $number]);
    }


    public function connectNumbersToPlayerByCode($code,$player_id, $number)
    {
        $number = $this->model->code($code)->first()->numbers()->where("number", $number)->first();
        return $number->update(["player_id" => $player_id]);
    }

    public function updateRandomWinnerNumbersByCode($code,$take_count)
    {
        $numbers = $this->model->code($code)->first()
                      ->numbers()
                      ->where("is_winner", false)
                      ->whereNull("player_id")
                      ->inRandomOrder()
                      ->take($take_count)
                      ->get();
        foreach($numbers as $number) {
            $number->is_winner = true;
            $number->save();
        }
    }
}
