<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name',
    'contact_person',
    'phone',
    'email',
    'tier',
    'contribution_amount',
    'status',
    'notes',
])]
class Sponsor extends Model
{
    protected function casts(): array
    {
        return [
            'contribution_amount' => 'integer',
        ];
    }
}
