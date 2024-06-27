<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'address';

    protected $fillable = [
        'addressName', 'name', 'city', 'zip', 'residentialFlag', 'locationGroup', 'customerId'
    ];

    /**
     * Find or create an address by customer ID and other attributes.
     */
    public static function findOrCreateByCustomerId(int $customerId, array $attributes)
    {
        $attributes['customer_id'] = $customerId;
        return self::updateOrCreate(
            ['customer_id' => $customerId],
            $attributes
        );
    }
}
