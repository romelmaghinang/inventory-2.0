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

        $account = Account::create([
            'typeId' => 10, 
        ]);
        $mobiles = $storeCustomerRequest->has('mobile') ? $storeCustomerRequest->mobile : [];

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
                    'cf',
                ]
            ) +
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
                'accountId' => $account->id, 
                'mobiles' => json_encode($mobiles),
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
                'accountId' => $account->id, 
            ]
        );
        

        return response()->json(
            [
                'message' => 'Customer Created Successfully!',
                'customer' => $customer,
                'address' => $address,
                'relatedData' => [
                    'currency' => $currency,
                    'customerStatus' => $customerStatus,
                    'taxRate' => $taxRate,
                    'priority' => $priority,
                    'paymentTerms' => $paymentTerms,
                    'carrier' => $carrier,
                    'carrierService' => $carrierService,
                    'shipTerms' => $shipTerms,
                    'quickBook' => $quickBook,
                    'addressType' => $addressType,
                    'state' => $state,
                    'country' => $country,
                ],
            ],
            Response::HTTP_CREATED
        );
    }



    /**
     * @OA\Get(
     *     path="/api/customer",
     *     summary="Retrieve all customers or filter by name",
     *     tags={"Customer"},
     *     description="Retrieve all customers or filter by name using query parameters or request body.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="The name of the customer to filter by",
     *         example="John Doe"
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe", description="Name of the customer to filter by")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", description="Array of customers", 
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Customer(s) retrieved successfully!")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function showCustomers(Request $request, $id = null): JsonResponse
    {
        $name = $request->query('name') ?? $request->input('name');
        
        if ($id) {
            $customer = Customer::find($id);
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found.'
                ], Response::HTTP_NOT_FOUND);
            }
    
            $qbClass = QBClass::find($customer->qbClassId);
            $currency = Currency::find($customer->currencyId);
            $paymentTerms = PaymentTerms::find($customer->defaultPaymentTermsId);
            $carrier = Carrier::find($customer->defaultCarrierId);
            $taxRate = TaxRate::find($customer->taxRateId);
    
            $customerData = $customer->toArray();
            $customerData['qbClass'] = $qbClass ? ['id' => $qbClass->id, 'name' => $qbClass->name] : null;
            $customerData['currency'] = $currency ? ['id' => $currency->id, 'name' => $currency->name] : null;
            $customerData['paymentTerms'] = $paymentTerms ? ['id' => $paymentTerms->id, 'name' => $paymentTerms->name] : null;
            $customerData['carrier'] = $carrier ? ['id' => $carrier->id, 'name' => $carrier->name] : null;
            $customerData['taxRate'] = $taxRate ? ['id' => $taxRate->id, 'name' => $taxRate->name] : null;
    
            return response()->json([
                'message' => 'Customer retrieved successfully!',
                'data' => $customerData,
            ], Response::HTTP_OK);
        }
    
        $query = Customer::query();
    
        if (!empty($name)) {
            $request->validate([
                'name' => 'string|exists:customers,name',
            ]);
    
            $query->where('name', 'like', '%' . $name . '%');
        }
    
        $perPage = $request->input('per_page', 100);
    
        $customers = $query->paginate($perPage);
    
        $customersData = $customers->items();
        foreach ($customersData as &$customer) {
            $qbClass = QBClass::find($customer['qbClassId']);
            $currency = Currency::find($customer['currencyId']);
            $paymentTerms = PaymentTerms::find($customer['defaultPaymentTermsId']);
            $carrier = Carrier::find($customer['defaultCarrierId']);
            $taxRate = TaxRate::find($customer['taxRateId']);
    
            $customer['qbClass'] = $qbClass ? ['id' => $qbClass->id, 'name' => $qbClass->name] : null;
            $customer['currency'] = $currency ? ['id' => $currency->id, 'name' => $currency->name] : null;
            $customer['paymentTerms'] = $paymentTerms ? ['id' => $paymentTerms->id, 'name' => $paymentTerms->name] : null;
            $customer['carrier'] = $carrier ? ['id' => $carrier->id, 'name' => $carrier->name] : null;
            $customer['taxRate'] = $taxRate ? ['id' => $taxRate->id, 'name' => $taxRate->name] : null;
        }
    
        return response()->json([
            'message' => 'Customers retrieved successfully!',
            'data' => $customersData,
            'pagination' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'next_page_url' => $customers->nextPageUrl(),
                'prev_page_url' => $customers->previousPageUrl(),
            ],
        ], Response::HTTP_OK);
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
    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
    
        $account = Account::findOrFail($customer->accountId);
        $address = Address::where('accountId', $customer->accountId)->firstOrFail();
    
        $currency = isset($request->currencyName) ? Currency::where('name', $request->currencyName)->firstOrFail() : null;
        $customerStatus = isset($request->status) ? CustomerStatus::where('name', $request->status)->firstOrFail() : null;
        $taxRate = isset($request->taxRate) ? TaxRate::where('name', $request->taxRate)->firstOrFail() : null;
        $priority = isset($request->defaultPriority) ? Priority::where('name', $request->defaultPriority)->firstOrFail() : null;
        $paymentTerms = isset($request->paymentTerms) ? PaymentTerms::where('name', $request->paymentTerms)->firstOrFail() : null;
        $carrier = isset($request->carrierName) ? Carrier::where('name', $request->carrierName)->firstOrFail() : null;
        $carrierService = isset($request->carrierService) ? CarrierService::where('name', $request->carrierService)->firstOrFail() : null;
        $shipTerms = isset($request->shippingTerms) ? ShipTerms::where('name', $request->shippingTerms)->firstOrFail() : null;
        $quickBook = isset($request->quickBooksClassName) ? QbClass::where('name', $request->quickBooksClassName)->firstOrFail() : null;
        $addressType = isset($request->addressType) ? AddressType::where('name', $request->addressType)->firstOrFail() : null;
        $state = isset($request->state) ? State::where('name', $request->state)->firstOrFail() : null;
        $country = isset($request->country) ? Country::where('name', $request->country)->firstOrFail() : null;
    
        if ($request->has('accountTypeId')) {
            $account->update(['typeId' => $request->accountTypeId]);
        }
    
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
            ) + 
            ($currency ? ['currencyId' => $currency->id] : []) +
            ($customerStatus ? ['statusId' => $customerStatus->id] : []) +
            ($taxRate ? ['taxRateId' => $taxRate->id] : []) +
            ($request->has('active') ? ['activeFlag' => $request->active] : []) +
            ($paymentTerms ? ['defaultPaymentTermsId' => $paymentTerms->id] : []) +
            ($carrier ? ['defaultCarrierId' => $carrier->id] : []) +
            ($carrierService ? ['carrierServiceId' => $carrierService->id] : []) +
            ($quickBook ? ['qbClassId' => $quickBook->id] : [])
        );
    
        $address->update(
            $request->only(
                [
                    'addressName',
                    'address',
                    'city',
                    'zip',
                ]
            ) +
            ($request->has('addressContact') ? ['piplineContactNum' => $request->addressContact] : []) +
            ($addressType ? ['typeId' => $addressType->id] : []) +
            ($request->has('isDefault') ? ['activeFlag' => $request->isDefault] : []) +
            ($state ? ['stateId' => $state->id] : []) +
            ($country ? ['countryId' => $country->id] : [])
        );
    
        return response()->json(
            [
                'message' => 'Customer Updated Successfully!',
                'customer' => $customer,
                'address' => $address,
                'relatedData' => [
                    'currency' => $currency,
                    'customerStatus' => $customerStatus,
                    'taxRate' => $taxRate,
                    'priority' => $priority,
                    'paymentTerms' => $paymentTerms,
                    'carrier' => $carrier,
                    'carrierService' => $carrierService,
                    'shipTerms' => $shipTerms,
                    'quickBook' => $quickBook,
                    'addressType' => $addressType,
                    'state' => $state,
                    'country' => $country,
                ],
            ],
            Response::HTTP_OK
        );
    }
    


    public function destroy(Request $request): JsonResponse
    {
        $customerId = $request->input('customerId');
    
        try {
            $customer = Customer::findOrFail($customerId);
    
            $customer->delete();
    
            return response()->json(
                [
                    'message' => 'Customer Deleted Successfully!',
                ],
                Response::HTTP_OK
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(
                [
                    'error' => 'Customer not found or does not exist.',
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'An error occurred while trying to delete the customer.',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    
}
