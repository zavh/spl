<?php

use Illuminate\Database\Seeder;

class AppDefaultConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('appdeafultconfig')->insert([
            'name' => 'default',
            'config' => '{"LoginController":{"modname":"LoginController","action":[{"name":"login","func":"showLoginForm","url":"login","selected":true},{"name":null,"func":"login","url":"login","selected":true},{"name":"logout","func":"logout","url":"logout","selected":true}]},"RegisterController":{"modname":"RegisterController","action":[{"name":"register","func":"showRegistrationForm","url":"register","selected":true},{"name":null,"func":"register","url":"register","selected":true}]},"ForgotPasswordController":{"modname":"ForgotPasswordController","action":[{"name":"password.request","func":"showLinkRequestForm","url":"password\/reset","selected":true},{"name":"password.email","func":"sendResetLinkEmail","url":"password\/email","selected":true}]},"ResetPasswordController":{"modname":"ResetPasswordController","action":[{"name":"password.reset","func":"showResetForm","url":"password\/reset\/{token}","selected":true},{"name":"password.update","func":"reset","url":"password\/reset","selected":true}]},"HomeController":{"modname":"HomeController","action":[{"name":"home","func":"index","url":"home","selected":true}]},"Closure":{"modname":"Closure","action":[]}}',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
