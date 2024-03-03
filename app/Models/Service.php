<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'price',
        'minQuantity',
        'maxQuantity',
        'serviceId',
        'image',
    ];

    public function imageUrl(): string | null
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function oders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('total_quantity', 'total_price');
    }

    public function parent()
    {
        return $this->hasMany(Service::class, 'serviceId');
    }

    public function parentServices()
    {
        return $this->hasMany(Service::class, 'serviceId');
    }

    public function descendants()
    {
        return $this->parent()->with('serviceId');
    }

    public function getAllDescendants()
    {
        $descendants = collect();

        $this->parentServices->each(function ($parentService) use (&$descendants) {
            $descendants->push($parentService);
            $descendants = $descendants->merge($parentService->getAllDescendants());
        });

        return $descendants;
    }
}
