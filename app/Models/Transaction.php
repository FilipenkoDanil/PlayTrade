<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transactable_type', 'transactable_id', 'amount'];

    const DEAL_PURCHASE = 'deal_purchase';
    const DEAL_SALE = 'deal_sale';
    const DEAL_CANCELED = 'deal_canceled';
    const WITHDRAWAL = 'withdrawal';
    const DEPOSIT = 'deposit';
}
