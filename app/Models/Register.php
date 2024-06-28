<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $table = 'register';
    protected $fillable = ['name'];
    public function getRegisterIdByName($name)
    {
        return $this->where('name', $name)->value('id');
    }
}
