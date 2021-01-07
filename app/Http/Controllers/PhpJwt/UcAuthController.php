<?php
/**
 * Description of this file
 *
 * @author  caobin <caobin@vchangyi.com>
 * @date    2020-04-28 11:36
 */

namespace App\Http\Controllers\PhpJwt;

use App\Http\Controllers\Controller;
use App\User;
use Firebase\JWT\JWT;

class UcAuthController extends Controller
{
    public function demo()
    {
        dd('demo');
    }

    public function login()
    {
        $model = User::first();

        if (!$model) {
            throw new \Exception('数据不存在', 0);
        }
        $data = [
            'id'    => $model->id,
            'name'  => $model->name,
            'phone' => $model->phone,
            'member' => 0
        ];
        $token = jwtToken($data);
        dd($token);

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJtaWNoYWVsa29ycy1qd3QiLCJzdWIiOiJ7XCJpZFwiOjEsXCJuYW1lXCI6XCJhZG1pblwiLFwicGhvbmVcIjpcIlwifSIsImlhdCI6MTU4ODA1NjkyNSwiZXhwIjoxNTg4MDU2OTMwfQ.C4WSQY-zAQHrZjlv2g6avX8rf_kYz8cImY_bbo1U0ZA';

        $decoded = JWT::decode($token, env('JWT_SECRET'), array('HS256'));
        dd($decoded);
    }

}