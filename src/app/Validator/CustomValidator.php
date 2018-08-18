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

    public static function validatePrefectureName($attribute,$value)
    {
        // Validation::active()->set_message('prefecture_code', '都道府県を選択してください。');
        $pref_list = ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県' , '沖縄県' ];

        return in_array($value, $pref_list, true);
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
