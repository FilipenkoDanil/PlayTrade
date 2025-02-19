<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    const DEAL_IN_PROGRESS = 1;
    const DEAL_COMPLETED = 2;
    const DEAL_CANCELED = 3;
    const DEAL_DISPUTED = 4;
    const TRANSACTION_PENDING = 5;
    const TRANSACTION_COMPLETED = 6;
    const TRANSACTION_FAILED = 7;
    const TRANSACTION_CANCELED = 8;
    const TRANSACTION_REFUNDED = 9;
}
