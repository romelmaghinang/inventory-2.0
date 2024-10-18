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
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/customer",
 *     tags={"Customer"},
 *     summary="Create a new customer",
 *     description="Store a new customer and their address.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Acme Corp"),
 *             @OA\Property(property="addressName", type="string", example="Headquarters"),
 *             @OA\Property(property="addressContact", type="string", example="John Doe"),
 *             @OA\Property(property="addressType", type="string", example="Home"),
 *             @OA\Property(property="isDefault", type="boolean", example=true),
 *             @OA\Property(property="address", type="string", example="456 Business Rd"),
 *             @OA\Property(property="city", type="string", example="Metropolis"),
 *             @OA\Property(property="state", type="string", example="California"),
 *             @OA\Property(property="zip", type="string", example="98765"),
 *             @OA\Property(property="country", type="string", example="United States"),
 *             @OA\Property(property="currencyName", type="string", example="US Dollar"),
 *             @OA\Property(property="currencyRate", type="number", format="float", example=1.0),
 *             @OA\Property(property="creditLimit", type="number", format="float", example=50000.0),
 *             @OA\Property(property="status", type="string", example="Normal"),
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="taxRate", type="string", example="Standard Tax"),
 *             @OA\Property(property="defaultPriority", type="string", example="High"),
 *             @OA\Property(property="number", type="string", example="CUST001"),
 *             @OA\Property(property="paymentTerms", type="string", example="Net 30"),
 *             @OA\Property(property="taxExempt", type="boolean", example=false),
 *             @OA\Property(property="taxExemptNumber", type="string", example="TX123456"),
 *             @OA\Property(property="url", type="string", example="https://www.acmecorp.com"),
 *             @OA\Property(property="carrierName", type="string", example="FedEx"),
 *             @OA\Property(property="carrierService", type="string", example="Ground"),
 *             @OA\Property(property="shippingTerms", type="string", example="Prepaid"),
 *             @OA\Property(property="quickBooksClassName", type="string", example="Sales"),
 *             @OA\Property(property="toBeEmailed", type="boolean", example=true),
 *             @OA\Property(property="toBePrinted", type="boolean", example=false),
 *             @OA\Property(property="cf", type="string", example="CustomFieldValue"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Customer created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Customer Created Successfully!"),
 *             @OA\Property(property="customer", type="object"),
 *             @OA\Property(property="address", type="object"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid input data")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Resource not found")
 *         )
 *     ),
 * )
 */
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
     * @OA\Get(
     *     path="/customer",
     *     summary="Get all customer",
     *     tags={"Customer"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function showAll(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json($customers, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/customer",
     *     summary="Get a specific customer by ID",
     *     tags={"Customer"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="customerId", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $customerId = $request->input('customerId');  
        $customer = Customer::findOrFail($customerId);
        
        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/customer",
     *     summary="Update a customer",
     *     tags={"Customer"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="customerId", type="integer", example=1),
     *             @OA\Property(property="name", type="string", maxLength=41, nullable=true),
     *             @OA\Property(property="addressName", type="string", nullable=false),
     *             @OA\Property(property="addressContact", type="string", nullable=false),
     *             @OA\Property(property="addressType", type="integer", nullable=false),
     *             @OA\Property(property="isDefault", type="boolean", nullable=false),
     *             @OA\Property(property="address", type="string", maxLength=90, nullable=true),
     *             @OA\Property(property="city", type="string", maxLength=30, nullable=true),
     *             @OA\Property(property="state", type="string", nullable=true),
     *             @OA\Property(property="zip", type="string", maxLength=10, nullable=true),
     *             @OA\Property(property="country", type="string", nullable=true),
     *             @OA\Property(property="resident", type="boolean", nullable=false),
     *             @OA\Property(property="main", type="string", nullable=false),
     *             @OA\Property(property="home", type="string", nullable=false),
     *             @OA\Property(property="work", type="string", nullable=false),
     *             @OA\Property(property="mobile", type="string", nullable=false),
     *             @OA\Property(property="fax", type="string", nullable=false),
     *             @OA\Property(property="email", type="string", format="email", nullable=false),
     *             @OA\Property(property="pager", type="string", nullable=false),
     *             @OA\Property(property="web", type="string", nullable=false),
     *             @OA\Property(property="other", type="string", nullable=false),
     *             @OA\Property(property="currencyName", type="string", nullable=false),
     *             @OA\Property(property="currencyRate", type="number", format="float", nullable=false),
     *             @OA\Property(property="group", type="string", nullable=false),
     *             @OA\Property(property="creditLimit", type="number", format="float", nullable=true),
     *             @OA\Property(property="status", type="string", nullable=true),
     *             @OA\Property(property="active", type="boolean", nullable=true),
     *             @OA\Property(property="taxRate", type="string", nullable=true),
     *             @OA\Property(property="salesman", type="integer", nullable=true),
     *             @OA\Property(property="defaultPriority", type="string", nullable=false),
     *             @OA\Property(property="number", type="string", maxLength=30, nullable=true),
     *             @OA\Property(property="paymentTerms", type="integer", nullable=true),
     *             @OA\Property(property="taxExempt", type="boolean", nullable=true),
     *             @OA\Property(property="taxExemptNumber", type="string", maxLength=30, nullable=true),
     *             @OA\Property(property="url", type="string", format="url", maxLength=30, nullable=true),
     *             @OA\Property(property="carrierName", type="string", nullable=true),
     *             @OA\Property(property="carrierService", type="string", nullable=true),
     *             @OA\Property(property="shippingTerms", type="string", nullable=true),
     *             @OA\Property(property="alertNotes", type="string", nullable=false),
     *             @OA\Property(property="quickBooksClassName", type="string", nullable=true),
     *             @OA\Property(property="toBeEmailed", type="boolean", nullable=true),
     *             @OA\Property(property="toBePrinted", type="boolean", nullable=true),
     *             @OA\Property(property="issuableStatus", type="string", nullable=true),
     *             @OA\Property(property="cf", type="string", nullable=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="customer", type="object"),
     *             @OA\Property(property="address", type="object"),
     *             @OA\Property(property="message", type="string", example="Customer Updated Successfully!")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function update(UpdateCustomerRequest $request): JsonResponse
    {
        $customerId = $request->input('customerId');  
        $customer = Customer::findOrFail($customerId);
        
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
            + [
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
            + [
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
 * @OA\Delete(
 *     path="/api/customer",
 *     summary="Delete a customer",
 *     tags={"Customer"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="customerId", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Customer deleted successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Customer Deleted Successfully!")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Customer not found"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
    public function destroy(Request $request): JsonResponse
    {
        $customerId = $request->input('customerId'); 
        $customer = Customer::findOrFail($customerId);
        
        $customer->delete();

        return response()->json(
            [
                'message' => 'Customer Deleted Successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
