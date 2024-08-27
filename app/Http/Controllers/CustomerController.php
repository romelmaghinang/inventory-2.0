<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Account;
use App\Models\Address;
use App\Models\AddressType;
use App\Models\Carrier;
use App\Models\CarrierService;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerStatus;
use App\Models\PaymentTerms;
use App\Models\Priority;
use App\Models\qbClass;
use App\Models\ShipTerms;
use App\Models\State;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function store(StoreCustomerRequest $storeCustomerRequest): JsonResponse
    {
        $currency = Currency::where('name', $storeCustomerRequest->currencyName)->firstOrFail();
        $customerStatus = CustomerStatus::where('name', $storeCustomerRequest->status)->firstOrFail();
        $taxRate = TaxRate::where('name', $storeCustomerRequest->taxRate)->firstOrFail();
        $priority = Priority::where('name', $storeCustomerRequest->defaultPriority)->firstOrFail();
        $paymentTerms = PaymentTerms::where('name', $storeCustomerRequest->paymentTerms)->firstOrFail();
        $carrier = Carrier::where('name', $storeCustomerRequest->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $storeCustomerRequest->carrierService)->firstOrFail();
        $shipTerms = ShipTerms::where('name', $storeCustomerRequest->shippingTerms)->firstOrFail();
        $quickBook = qbClass::where('name', $storeCustomerRequest->quickBooksClassName)->firstOrFail();
        $addressType = AddressType::where('name', $storeCustomerRequest->addressType)->firstOrFail();
        $state = State::where('name', $storeCustomerRequest->state)->firstOrFail();
        $country = Country::where('name', $storeCustomerRequest->country)->firstOrFail();

        $customer = Customer::create(
            $storeCustomerRequest->only(
                [
                    'name',
                    'currencyRate',
                    'creditLimit',
                    'number',
                    'taxExempt',
                    'taxExemptNumber',
                    'url',
                    'toBeEmailed',
                    'toBePrinted',
                    'cf'
                ]
            )
                +
                [
                    'currencyId' => $currency->id,
                    'statusId' => $customerStatus->id,
                    'activeFLag' => $storeCustomerRequest->active,
                    'taxRateId' => $taxRate->id,
                    'defaultPaymentTermsId' => $paymentTerms->id,
                    'defaultCarrierId' => $carrier->id,
                    'carrierServiceId' => $carrierService->id,
                    'qbClassId' => $quickBook->id,
                    'defaultShipTermsId' => $shipTerms->id,
                ]
        );

        $address = Address::create(
            $storeCustomerRequest->only(
                [
                    'addressName',
                    'address',
                    'city',
                    'zip',

                ]
            ) +
                [
                    'piplineContactNum' => $storeCustomerRequest->addressContact,
                    'typeId' => $addressType->id,
                    'activeFlag' => $storeCustomerRequest->isDefault,
                    'stateId' => $state->id,
                    'countryId' => $country->id,
                    'name' => $storeCustomerRequest->addressName,
                ]
        );

        return response()->json(
            [
                'customer' => $customer,
                'address' => $address,
                'message' => 'Customer Created Successfully!',
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $account = Account::findOrFail($customer->account_id);
        $address = Address::where('customer_id', $customer->id)->firstOrFail();
        $currency = Currency::where('name', $request->currencyName)->firstOrFail();
        $customerStatus = CustomerStatus::where('name', $request->status)->firstOrFail();
        $taxRate = TaxRate::where('name', $request->taxRate)->firstOrFail();
        $priority = Priority::where('name', $request->defaultPriority)->firstOrFail();
        $paymentTerms = PaymentTerms::where('name', $request->paymentTerms)->firstOrFail();
        $carrier = Carrier::where('name', $request->carrierName)->firstOrFail();
        $carrierService = CarrierService::where('name', $request->carrierService)->firstOrFail();
        $shipTerms = ShipTerms::where('name', $request->shippingTerms)->firstOrFail();
        $quickBook = QbClass::where('name', $request->quickBooksClassName)->firstOrFail();
        $addressType = AddressType::where('name', $request->addressType)->firstOrFail();
        $state = State::where('name', $request->state)->firstOrFail();
        $country = Country::where('name', $request->country)->firstOrFail();


        $account->update(['typeId' => $request->accountTypeId]);

        $customer->update(
            $request->only(
                [
                    'name',
                    'currencyRate',
                    'creditLimit',
                    'number',
                    'taxExempt',
                    'taxExemptNumber',
                    'url',
                    'toBeEmailed',
                    'toBePrinted',
                    'cf'
                ]
            )
                +
                [
                    'currencyId' => $currency->id,
                    'statusId' => $customerStatus->id,
                    'activeFLag' => $request->active,
                    'taxRateId' => $taxRate->id,
                    'defaultPaymentTermsId' => $paymentTerms->id,
                    'defaultCarrierId' => $carrier->id,
                    'carrierServiceId' => $carrierService->id,
                    'qbClassId' => $quickBook->id,
                ]
        );

        $address->update(
            $request->only(
                [
                    'addressName',
                    'address',
                    'city',
                    'zip',
                ]
            )
                +
                [
                    'piplineContactNum' => $request->addressContact,
                    'typeId' => $addressType->id,
                    'activeFlag' => $request->isDefault,
                    'stateId' => $state->id,
                    'countryId' => $country->id,
                ]
        );

        return response()->json(
            [
                'customer' => $customer,
                'address' => $address,
                'message' => 'Customer Updated Successfully!',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(
            [
                'message' => 'Customer Deleted Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
