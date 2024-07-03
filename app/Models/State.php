<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'state';
    protected $fillable = ['name'];

    public function getStateIdByName($stateName)
    {
        $state = $this->where('name', $stateName)->first();
        return $state->id;
    }

    public $timestamps = false;
}
