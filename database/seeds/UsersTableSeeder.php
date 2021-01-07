<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \App\User();
        $model->account = "admin";
        $model->password = 123456;
        $model->name = "admin";
        $model->head_image = "";
        $model->last_login_ip = "127.0.0.1";
        $model->save();
    }
}
