<?php
namespace App\Validator;

use Hash;
use App\Admin;

class CustomValidator extends \Illuminate\Validation\Validator
{
    public function validateCheckTotalNumberExceeded($attribute, $value, $parameters)
    {
        return $value <= $this->data["total"];
    }

    public function validateKatakana($attribute, $value, $parameters)
    {
        // Validation::active()->set_message('katakana', ':labelはカタカナのみで入力して下さい');
        mb_regex_encoding("UTF-8");
        return preg_match("/^[ァ-ヶー]+$/u", $value) === 1;
    }

    public static function validatePrefectureCode($attribute,$value)
    {
        // Validation::active()->set_message('prefecture_code', '都道府県を選択してください。');
        return (0 < $value && $value <= 47);
    }

    public static function validatePhonenumber($attribute,$value)
    {
        // Validation::active()->set_message('phonenumber', ':labelの形式が正しくありません');
        return preg_match("/\A0[0-9]{9,10}\z/", $value) === 1;
    }

    // public static function validateUniqueSynergy($attribute,$value,$parameters)
    // {
    //     $api_key = env("PERSONAL_SYNERGY_API_KEY");
    //     $client = new \SoapClient(base_path(env('PERSONAL_SYNERGY_WSDL')) , ['trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE]);
    //     $ret = $client->count([
    //         "key" => $api_key ,
    //         'searchConditions' => [
    //             "searchCondition" => [
    //               ["column" => $parameters[0],  "condition" => ["textData" => $value  ] ]
    //             ],
    //         ]
    //     ]);
    //
    //     // Validation::active()->set_message('unique_synergy', '既に登録されているデータです');
    //     return ! ((int)$ret->count > 0);
    // }

    public static function validateCheckVoteChoice($attribute,$value)
    {

        $rows = array_filter(explode("\n",$value));
        foreach($rows as $row) {
            $cells = array_filter(explode(",",$row));
            if (count($cells) !== 2) {
                return false;
            }
        }
        return true;

    }

    public function validateOldPassword($attribute, $value, $parameters)
    {
        return Hash::check($value, $parameters[0]);
    }

    public function validateUniqueInProject($attribute, $value, $parameters)
    {
        return $parameters[0]::where("id","!=",$parameters[1])->where($attribute,$value)->project()->count() === 0;
    }

}
