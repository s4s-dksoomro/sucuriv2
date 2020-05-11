<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Zone;
use App\CustomCertificate;


class FetchCustomCertificates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$zone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Zone $zone)
    {
        //
        $this->zone=$zone;
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

        $key     = new \Cloudflare\API\Auth\APIKey($this->zone->cfaccount->email, $this->zone->cfaccount->user_api_key);
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $zones   = new \Cloudflare\API\Endpoints\Zones($adapter);



      
//             dd($zones->addZoneCustomCertificate($this->zone->zone_id,'-----BEGIN CERTIFICATE-----
// MIIE+jCCA+KgAwIBAgIQaiO+lR8k4UGiK453bULDdjANBgkqhkiG9w0BAQsFADCB
// hDELMAkGA1UEBhMCVVMxHTAbBgNVBAoTFFN5bWFudGVjIENvcnBvcmF0aW9uMR8w
// HQYDVQQLExZTeW1hbnRlYyBUcnVzdCBOZXR3b3JrMTUwMwYDVQQDEyxTeW1hbnRl
// YyBDbGFzcyAzIFNlY3VyZSBTZXJ2ZXIgU0hBMjU2IFNTTCBDQTAeFw0xNTA5MDIw
// MDAwMDBaFw0xODA5MTQyMzU5NTlaMHgxCzAJBgNVBAYTAkNBMRAwDgYDVQQIDAdP
// bnRhcmlvMQ8wDQYDVQQHDAZXaGl0YnkxFTATBgNVBAoMDFNvZnRNb2MgSW5jLjEV
// MBMGA1UECwwMU29mdE1vYyBJbmMuMRgwFgYDVQQDDA93d3cuc29mdG1vYy5jb20w
// ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDHZ0RjiyX/eLXz5nj9Wt38
// LjcF7Eito8yshpgkPtZe+YfhORhQ8G6HCkgpyg6PkvDOWNzfi6Cqf3/kZwVS44nk
// /kqy+CYwWZPLsh/D0V8baN5Yq+ZsBxgSKqf5nuekaBnOxqhwxYCiH3f9lqDO/lPE
// i4dRdXi0wjoi3Qe+R1T0pyY67Ymh4+nrTaNhJp/pFuQasU2woS8felLIj+RKztsT
// 5KujmhNaoeqVKlK9tiE8NOfAPjEUYu7bDAiOzG39bTQQsoGc19dvSrbH6vfb3pCr
// EHUMTZQrwO6aYBUs4EsWvRRgOexvppHM+/7Dlso4rp4kw6/sXsOwrruM1k77v1N3
// AgMBAAGjggFxMIIBbTAnBgNVHREEIDAegg93d3cuc29mdG1vYy5jb22CC3NvZnRt
// b2MuY29tMAkGA1UdEwQCMAAwDgYDVR0PAQH/BAQDAgWgMCsGA1UdHwQkMCIwIKAe
// oByGGmh0dHA6Ly9zZy5zeW1jYi5jb20vc2cuY3JsMGEGA1UdIARaMFgwVgYGZ4EM
// AQICMEwwIwYIKwYBBQUHAgEWF2h0dHBzOi8vZC5zeW1jYi5jb20vY3BzMCUGCCsG
// AQUFBwICMBkMF2h0dHBzOi8vZC5zeW1jYi5jb20vcnBhMB0GA1UdJQQWMBQGCCsG
// AQUFBwMBBggrBgEFBQcDAjAfBgNVHSMEGDAWgBTbYiD7fQKJfNI7b8fkMmwFUh2t
// sTBXBggrBgEFBQcBAQRLMEkwHwYIKwYBBQUHMAGGE2h0dHA6Ly9zZy5zeW1jZC5j
// b20wJgYIKwYBBQUHMAKGGmh0dHA6Ly9zZy5zeW1jYi5jb20vc2cuY3J0MA0GCSqG
// SIb3DQEBCwUAA4IBAQBNhZVdFGeNZPFxRuj/1x82UaXd1gz+VEggIPZ62Kho0y/k
// YX9c1Qc2zCIEVaEKPop6tbz46gzow7o0LS4NQm27Ra1F/tYDb9omi/X09E3dwa+M
// nhXKtNEKscKs0gy3Ap5RjOl1DOphBvhxnchDpZnkUE8mb2kTzqRaGN6FAIzzxjbl
// zGbBYe3yj+JLns5Hmm3axNTnmt4SvG2HKRyLR6AFrKx4wQRDY0xr7ky9IGz7sjY0
// 0fLt27h14oTobbbQZomrpGCsLGjjGLytXDRLMcZNOE8QM5LoeBRcIfYrD8eM9j7Q
// 6OpTtAhT8QaHFbid3hlB5Fu7qHYfuaqMm0oUfZSF
// -----END CERTIFICATE-----','-----BEGIN PRIVATE KEY-----
// MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDHZ0RjiyX/eLXz
// 5nj9Wt38LjcF7Eito8yshpgkPtZe+YfhORhQ8G6HCkgpyg6PkvDOWNzfi6Cqf3/k
// ZwVS44nk/kqy+CYwWZPLsh/D0V8baN5Yq+ZsBxgSKqf5nuekaBnOxqhwxYCiH3f9
// lqDO/lPEi4dRdXi0wjoi3Qe+R1T0pyY67Ymh4+nrTaNhJp/pFuQasU2woS8felLI
// j+RKztsT5KujmhNaoeqVKlK9tiE8NOfAPjEUYu7bDAiOzG39bTQQsoGc19dvSrbH
// 6vfb3pCrEHUMTZQrwO6aYBUs4EsWvRRgOexvppHM+/7Dlso4rp4kw6/sXsOwrruM
// 1k77v1N3AgMBAAECggEAKQhZDFrf8Ng4KP5uO0Rtla006WUb949TWNVBjYPYvSaA
// DZqgJFe1hthYzTClGmpUX0HuAZpL74nZGXkRoWLtR1AEsxtZUG2+ehYnbzYwagWE
// a57EBcrX6zUX58gJRs2dMe2zT7F0rOfo2ygZ4z52omVL7TlNI3UsXWw6Ya9wDf/S
// NCG0/7dXdQYFkeFzlScrd0kn79VQcrJqUqqMJ5G8LyPAdR2XQKs1qwgm8+YDDAw0
// qE5Nl+elQgpAxX3ez4zt1HIE85wgbEU/6X8IiM13NjeVgFbD3R8Jwl8a2PgauSX5
// 8EACzJXN6gUxt4F2VuStaeF4jCZsz5HGqCglD+zREQKBgQDwqhs74821Zcibk0at
// J16aAdKyuTkHugBrC2BojP987fyTe/YcDENHBOZ+1kiwvr1SS6Z2f3hvgyX+aY/c
// l5fldiwVFfr/em5wsND/6x1r3OUTEXJW2kTgc/pwK+AbwYh3IdT4TQp69wdHKtNs
// ZBRS0WkozrTIsMR5829DRQCTnQKBgQDUHBR0qvvVwNTSgLW9tS1TH84uXe1oIIPL
// eOhIaP9mBztcIlzGLJJhFOtLAAx1RclFkRYeAkVAGWEII102XjUHgakiurPqlwQ1
// tompbmtq+KM04OvJ3jRvnjyx32vcPbEU8ardpf8EbQlpyuSWz0StOj3k0kjPHKTG
// hFrCg/MpIwKBgQDuNEB+d8ztBdCYhB5JVpVhM/q7IW/cQPMXAIytDxn2Kox74g9H
// 1a9lhOkrk6chIbm70dryoNqw7QtXFF7rRTR4Tw301Ou5oRHdAnXrSGi3kU0/IV6d
// rq7Lxp+UFSld76HF4LuuBHsiGI2gmDfpqekfy9wdIvN3TNc7qYJv/8VBVQKBgHj/
// 08OCTstvh3jWJ9ci5cpHmIzm1CnUcpq+THQSQa/obDpw98+tWYu8LUXJr1GvD9R5
// oc5YqTyZvbqwwdnkAAhaNSw0qbInmCU0Dm/zJ6AMWr4tmRS9h9gswp8NvzASmVRD
// UJ/EtCfIM9h//8rwTlMrqGdCdnp/8pe0pTnVn/9VAoGBAJD8iEXuPboReG4gzCmA
// 8fs3LHffYXX067tVZSFglKsGCGYuxFN7/jGXuyEHDU+sCXx0xHfGLyqragViMqya
// L1EqXiNp6FgXYyL9Ryr4xWafgEw1TF3t/ThENtNR11Nbs2s4+gjMHtAs9EdVw3sv
// Na0qhg32vpakpueuF3+OCLqj
// -----END PRIVATE KEY-----'));
            $custom_certificates=$zones->getZoneCustomCertificates($this->zone->zone_id);

      
            foreach ($custom_certificates as $custom_certificate) {
                # code...
                $custom_certificate=(array)$custom_certificate;
                $custom_certificate['hosts']=implode(",", $custom_certificate['hosts']);
                $custom_certificate['zone_id']=$this->zone->id;
                $check['resource_id']=$custom_certificate['id'];
                array_forget($custom_certificate,['id','uploaded_on','modified_on']);


                CustomCertificate::updateOrCreate($check, $custom_certificate);
                //CustomCertificate::create($custom_certificate);
            }


       


        
    }
}
