<?php

namespace App\Models;

use DB;
use Helper;

class Member extends BaseModel
{
    public static function loginByUnionId($openId)
    {
        $user_data = false;

        $user_data = DB::table('t_member')->where('openid', $openId)->first();

        if (empty($user_data) || $user_data == NULL) {
            return false;
        }

        $updateData['logined_at'] = date('Y-m-d H:i:s');

        DB::table('t_member')->where('id', $user_data->id)->update($updateData);

        return $user_data;
    }
}