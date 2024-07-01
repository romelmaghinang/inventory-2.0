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
        // Attempt to find the priority by name
        $priority = $this->where('name', $name)->first();

        // If the priority exists, return its id
        if ($priority) {
            return $priority->id;
        }

        // If the priority does not exist, return null or handle as needed
        return null;
    }
}
