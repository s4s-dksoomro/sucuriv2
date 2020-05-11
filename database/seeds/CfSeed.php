<?php

use Illuminate\Database\Seeder;
use App\Cfaccount;
use App\Zone;
use App\User;

class CfSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   






        //  $Zone = Zone::create([
        
        //     'name' => 'wasiflaeeq.com',
        //     'zone_id' => '4dd68be91f507a3186b09b1e0a44f045',
        //     'user_id' => 2,
        //     'cfaccount_id' => 1,
            

        // ]
        
        // );
        

        // $Zone = Zone::create([
        
        //     'name' => 'liversion.com',
        //     'zone_id' => '04ff4f82fccbbca8917257b2aae7e2a3',
        //     'user_id' => 2,
        //     'cfaccount_id' => 1,
            

        // ]);

        // $Zone = Zone::create([
        
        //     'name' => 'softmoc.com',
        //     'zone_id' => '6af67be27b884e0a45acd233060b640b',
        //     'user_id' => 2,
        //     'cfaccount_id' => 2,
        //     'plan' => 'enterprise'
            

        // ]);

        // $Zone = Zone::create([
        
        //     'name' => 'jasarat.org',
        //     'zone_id' => 'a8362e8ad26223453bcde884a811d3ce',
        //     'user_id' => 2,
        //     'cfaccount_id' => 3,
            
            

        // ]);





        $users=$host_user =[];

foreach ($host_user as $key => $value) {
    # code...
    $host_user[$key]['user_api_key']=$host_user[$key]['api_key'];

    // if($host_user[$key]['user_key']==NULL)
    // {
    //     $host_user[$key]['user_key']="";
    // }

    array_forget($host_user[$key],["password","username","api_key"]);
}
        
    
    // Cfaccount::insert($host_user);



    foreach ($users as $key => $value) {
    # code...
   
    if($users[$key]['username']==NULL)
    {
        $users[$key]['username']="";
    }
    $users[$key]['password_updated']=0;
    $users[$key]['old_password']=$users[$key]['password'];
    $users[$key]['name']=$users[$key]['username'];

    array_forget($users[$key],["username","api_key"]);

    $user=User::create($users[$key]);
    $user->assign('organization');

}





    }
}
