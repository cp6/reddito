<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount', 'created_at'];

    public static function idForDomain(string $domain): int
    {
        return Cache::remember("domain.$domain", now()->addMonths(2), function () use ($domain) {
            $exists = self::where('name', $domain)->first();

            if (isset($exists->id)) {
                return $exists->id;
            }

            $domain_obj = self::create(['name' => $domain, 'created_at' => date('Y-m-d H:i:s')]);

            return $domain_obj->id;
        });
    }

}
