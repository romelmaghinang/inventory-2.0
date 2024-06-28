<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;
    protected $table = 'priority'; // Ensure this matches your table name

    protected $fillable = ['name'];

    public function getPriorityIdByName($name)
    {
        return $this->where('name', $name)->value('id');
    }
}
