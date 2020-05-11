<?php

namespace App\Jobs;


use App\Spaccount;


class FetchSpZones 
{


    public $user_id, $spaccount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Spaccount $spaccount)
    {
        //
        $this->spaccount=$spaccount;
        $this->user_id=auth()->user()->id;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

      

        $stackPath= new \MaxCDN($this->spaccount->alias,$this->spaccount->key,$this->spaccount->secret);

      

        
      
      
            $zones=$stackPath->get('/sites')->data->zones;
            
            //dd($zones);
    


                return $zones;

            


       


        
    }
}
