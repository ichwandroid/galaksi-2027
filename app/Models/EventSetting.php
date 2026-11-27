<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'event_year',
    'event_date_label',
    'open_gate_label',
    'venue_name',
    'venue_city',
    'ticket_form_url',
    'tenant_url',
    'sponsor_url',
])]
class EventSetting extends Model
{
    /**
     * Retrieve the singleton event setting row or a transient default instance.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static([
            'event_year' => '2027',
            'event_date_label' => 'Sabtu, 16 Januari 2027',
            'open_gate_label' => '07.00 WIB',
            'venue_name' => 'Graha Cakrawala',
            'venue_city' => 'Universitas Negeri Malang',
            'ticket_form_url' => url('/tiket'),
            'tenant_url' => url('/tenant'),
            'sponsor_url' => url('/sponsor'),
        ]);
    }
}
