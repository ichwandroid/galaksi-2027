<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_code',
    'buyer_name',
    'buyer_email',
    'buyer_phone',
    'category',
    'quantity',
    'total_amount',
    'status',
    'notes',
])]
class TicketOrder extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'total_amount' => 'integer',
        ];
    }
}
