<?php

namespace App\Http\Requests\Receiving;

use Illuminate\Foundation\Http\FormRequest;

class ReceivingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Always return true to allow request; add custom authorization logic if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'PONum' => 'required|string|max:25',
            'Fulfill' => 'required|boolean',
            'VendorPartNum' => 'nullable|string|max:50',
            'Qty' => 'nullable|numeric',
            'Location' => 'nullable|string|max:255',
            'Date' => 'nullable|date_format:m/d/Y',
            'ShippingTrackingNumber' => 'nullable|string|max:50',
            'ShippingPackageCount' => 'nullable|numeric',
            'ShippingCarrier' => 'nullable|string|max:50',
            'ShippingCarrierService' => 'nullable|string|max:50',
            'Tracking' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'PONum.required' => 'The Purchase Order Number is required.',
            'PONum.string' => 'The Purchase Order Number must be a string.',
            'PONum.max' => 'The Purchase Order Number cannot exceed 25 characters.',
            'Fulfill.required' => 'The fulfillment status is required.',
            'Fulfill.boolean' => 'The fulfillment status must be a true/false value.',
            'VendorPartNum.string' => 'The Vendor Part Number must be a string.',
            'VendorPartNum.max' => 'The Vendor Part Number cannot exceed 50 characters.',
            'Qty.numeric' => 'The quantity must be a numeric value.',
            'Location.string' => 'The location must be a string.',
            'Location.max' => 'The location cannot exceed 255 characters.',
            'Date.date_format' => 'The date must be in the format m/d/Y.',
            'ShippingTrackingNumber.string' => 'The Shipping Tracking Number must be a string.',
            'ShippingTrackingNumber.max' => 'The Shipping Tracking Number cannot exceed 50 characters.',
            'ShippingPackageCount.numeric' => 'The Shipping Package Count must be a numeric value.',
            'ShippingCarrier.string' => 'The Shipping Carrier must be a string.',
            'ShippingCarrier.max' => 'The Shipping Carrier cannot exceed 50 characters.',
            'ShippingCarrierService.string' => 'The Shipping Carrier Service must be a string.',
            'ShippingCarrierService.max' => 'The Shipping Carrier Service cannot exceed 50 characters.',
            'Tracking.string' => 'The tracking information must be a string.',
            'Tracking.max' => 'The tracking information cannot exceed 50 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'PONum' => 'Purchase Order Number',
            'Fulfill' => 'Fulfillment Status',
            'VendorPartNum' => 'Vendor Part Number',
            'Qty' => 'Quantity',
            'Location' => 'Location',
            'Date' => 'Date',
            'ShippingTrackingNumber' => 'Shipping Tracking Number',
            'ShippingPackageCount' => 'Shipping Package Count',
            'ShippingCarrier' => 'Shipping Carrier',
            'ShippingCarrierService' => 'Shipping Carrier Service',
            'Tracking' => 'Tracking',
        ];
    }
}
