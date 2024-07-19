<?php

namespace App\Enums\Enum;

enum SalesOrderItemTypeEnum: string
{
    case assocPric = 'Assoc. Price';
    case creditReturn = 'Credit Return';
    case discountAmount = 'Discount Amount';
    case discountPercentage = 'Discount Percentage';
    case dropShip = 'Drop Ship';
    case kit = 'Kit';
    case micsCredit = 'Misc. Price';
    case note = 'Note';
    case sale = 'Sale';
    case shipping = 'Shipping';
    case subTotal = 'Subtotal';
    case tax = 'Tax';
}
