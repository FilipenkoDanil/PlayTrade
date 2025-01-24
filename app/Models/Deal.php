<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['quantity', 'price', 'buyer_id', 'offer_id', 'offer_title', 'offer_description', 'offer_attributes', 'offer_server', 'status_id'];
}
