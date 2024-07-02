<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = ['accountId','countryId', 'stateId'];

    public function getOrCreateAddress($accountId, $countryName, $stateName): array
    {
        // Attempt to find the address by countryName and stateName
        $address = $this->where('countryId', $this->getCountryIdByName($countryName))
            ->where('stateId', $this->getStateIdByName($stateName))
            ->where('accountId', $this->getAccountIdByName($accountId))
            ->first();

        // If the address exists, return its details
        if ($address) {
            return [
                'addressId' => $address->accountId,
                'countryId' => $address->countryId,
                'stateId' => $address->stateId
            ];
        }

        // If the address does not exist, create a new one and return its details
        $newAddress = $this->create([
            'accountId' => $this->getAccountIdByName($accountId),
            'countryId' => $this->getCountryIdByName($countryName),
            'stateId' => $this->getStateIdByName($stateName)
        ]);

        return [
            'countryId' => $newAddress->countryId,
            'stateId' => $newAddress->stateId
        ];
    }

    public function getCountryIdByName($countryName)
    {
        // Logic to retrieve countryId by countryName
        $country = Country::where('name', $countryName)->first();

        if ($country) {
            return $country->id;
        }

        // If the country does not exist, create a new one and return its id
        $newCountry = Country::create(['name' => $countryName]);

        return $newCountry->id;
    }

    public function getStateIdByName($stateName)
    {
        // Logic to retrieve stateId by stateName
        $state = State::where('name', $stateName)->first();

        if ($state) {
            return $state->id;
        }

        // If the state does not exist, create a new one and return its id
        $newState = State::create(['name' => $stateName]);

        return $newState->id;
    }

    public function getAccountIdByName($accountName)
    {
        // Logic to retrieve accountId by mame
        $account = Accounttype::where('name', $accountName)->first();

        if ($account) {
            return $account->id;
        }

        // If the account does not exist, create a new one and return its id
        $newAccount = Accounttype::create(['name' => $accountName]);

        return $newAccount->id;
    }

    public $timestamps = false;
}
