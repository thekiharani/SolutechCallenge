<?php

namespace App\Models;

use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasAuthor, SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * belongs to a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * can have many orders
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details');
    }

    /**
     * can be supplied by many suppliers
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'suppliers_products');
    }

}
