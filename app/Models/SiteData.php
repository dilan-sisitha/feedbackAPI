<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteData extends Model
{
    use HasFactory;

    protected $fillable= [
        'user_id',
        'sheet_id',
        'site_name',
        'site_section'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
