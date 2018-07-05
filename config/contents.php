<?php

return [
  "lottery" => [
    "state" => [
      "active" => ["type" => 1, "label" => "公開中" ,"css_style" => "success"],
      "stand_by" => ["type" => 2, "label" => "公開待機中","css_style" => "warning"],
      "finish" => ["type" => 3, "label" => "公開終了","css_style" => "danger"],
      "inactive" => ["type" => 4, "label" => "非公開","css_style" => "danger"],
      "full_entry" => ["type" => 5, "label" => "応募上限到達","css_style" => "warning"],
    ]
  ],
  "entry" => [
    "state" => [
      "init" => 0,
      "lose" => 1,
      "win" => 2,
      "win_posting_completed" => 3,
      "win_posting_expired" => 4,
      "win_special" => 5,
    ],
    "state_data" => [
      ["label" =>  "初期状態", "css_style" => "warning"],
      ["label" =>  "落選", "css_style" => "danger"],
      ["label" =>  "当選（未応募）", "css_style" => "success"],
      ["label" =>  "当選", "css_style" => "info"],
      ["label" =>  "当選（有効期限切れ）", "css_style" => "danger"],
      ["label" =>  "特別当選（未応募）", "css_style" => "success"]
    ]
  ],
];
