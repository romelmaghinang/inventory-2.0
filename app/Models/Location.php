<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';
    protected $fillable = ['activeFlag', 'locationGroupId', 'description', 'name', 'parentId', 'pickable', 'receivable'];

    public function getLocationGroup($name)
    {
        // Attempt to find the location group by name
        $location = $this->where('name', $name)->first();

        return ['locationGroupId' => $location->locationGroupId];

    }

    public function createLocationGroup($name, $countedAsAvailable = 1, $description = '', $parentId = null, $pickable = 1, $receivable = 1)
    {
        $newLocation = $this->createLocationGroup([
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
