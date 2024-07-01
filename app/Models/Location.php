<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';
    protected $fillable = ['activeFlag', 'locationGroupId', 'description', 'name', 'parentId', 'pickable', 'receivable'];

    public function getLocationGroup($name, $countedAsAvailable = 1, $description = '', $parentId = null, $pickable = 1, $receivable = 1)
    {
        // Attempt to find the location group by name
        $location = $this->where('name', $name)->first();

        // If the location group exists, return its id
        if ($location) {
            return ['locationGroupId' => $location->locationGroupId];
        }

        // If the location group does not exist, create a new one and return its id
        $newLocation = $this->create([
            'activeFlag' => 1,
            'description' => $description,
            'countedAsAvailable' => $countedAsAvailable,
            'name' => $name,
            'parentId' => $parentId,
            'pickable' => $pickable,
            'receivable' => $receivable
        ]);

        return ['locationGroupId' => $newLocation->locationGroupId];
    }

    public $timestamps = false;
}
