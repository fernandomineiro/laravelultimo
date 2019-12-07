<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = "perfil";

    public $timestamps = false;

    public function Users()
    {
        return $this->belongsToMany(Users::class);
    }
}
