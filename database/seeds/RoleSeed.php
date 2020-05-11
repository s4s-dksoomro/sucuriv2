<?php

use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('administrator')->to(['users_manage','resellers_manage','analytics','DNS','crypto','firewall','WAF','speed','caching','network','scrape']);
        Bouncer::allow('res')->to(['users_manage','branding','cloudflare','stackpath']);
        Bouncer::allow('org')->to(['analytics','DNS','crypto','firewall','WAF','speed','caching','network','scrape']);

 Bouncer::allow('org')->to('pagerule');
        Bouncer::allow('administrator')->to('pagerule');
  Bouncer::allow('res')->to(['loadbalancer']);
Bouncer::allow('org')->to(['seo','origin','loadbalancer']);
Bouncer::allow('administrator')->to(['seo','origin','loadbalancer']);


    }
}
