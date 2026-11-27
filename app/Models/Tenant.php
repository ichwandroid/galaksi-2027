<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'business_name',
    'owner_name',
    'phone',
    'email',
    'category',
    'booth_code',
    'status',
    'notes',
])]
class Tenant extends Model
{
}
