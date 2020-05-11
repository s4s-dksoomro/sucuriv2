<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@s4scorp.com',
            'password' => bcrypt('password')
        ]);
        $user->assign('administrator');


        $user = User::create([
            'name' => 'Wasif Laeeq',
            'email' => 'wasiflaeeq@gmail.com',
            'password' => bcrypt('password')
        ]);
        $user->assign('organization');
        User::find(1)->allow('pagerule');


       

        User::find(1)->allow(['seo','origin']);
        

        
    }


}
