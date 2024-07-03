<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;

    protected $table = 'priority'; // Ensure this matches your table name

    protected $fillable = ['id', 'name'];

    public function getPriorityIdByName($name)
    {
        $priority = $this->where('name', $name)->first();

        return $priority->id;
    }

    public $timestamps = false;
}
