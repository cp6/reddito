<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Fetch extends Model
{
    use HasFactory;

    protected $table = 'fetches';

    protected $fillable = ['results', 'inserted', 'updated'];

    public string $url = 'https://www.reddit.com/r/all/new.json?sort=new&limit=50';
    public string $referer = 'https://www.reddit.com/';
    public string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:102.0) Gecko/20100101 Firefox/102.0';

    public function __construct(string $url = 'https://www.reddit.com/r/all/new.json?sort=new&limit=50', string $referer = 'https://www.reddit.com/', string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:102.0) Gecko/20100101 Firefox/102.0')
    {
        $this->url = $url;
        $this->referer = $referer;
        $this->user_agent = $user_agent;
    }

    public function getData(bool $log_debug = true): bool|string
    {
        $crl = curl_init($this->url);
        curl_setopt($crl, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($crl, CURLOPT_REFERER, $this->referer);
        curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($crl, CURLOPT_TIMEOUT, 10);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        $call_response = curl_exec($crl);
        curl_close($crl);
        if ($log_debug){
            $d = curl_getinfo($crl);
            Log::debug("HTTP:{$d['http_code']} TT:{$d['total_time']} CT:{$d['connect_time']} TRANST:{$d['starttransfer_time']} SIZE:{$d['download_content_length']} IP:{$d['primary_ip']} URL:{$d['url']}");
        }
        return $call_response;
    }

}
