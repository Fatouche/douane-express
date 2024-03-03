<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'date',
        'order_number',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('total_quantity', 'total_price');
    }
}
