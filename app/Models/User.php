<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function buyerDeals(): HasMany
    {
        return $this->hasMany(Deal::class, 'buyer_id', "id")->orderBy('created_at', 'desc');
    }

    public function sellerDeals(): HasManyThrough
    {
        return $this->hasManyThrough(Deal::class, Offer::class, 'seller_id', 'offer_id', 'id', 'id')->orderBy('created_at', 'desc');
    }

    public function allDeals()
    {
        return $this->buyerDeals->merge($this->sellerDeals)->sortByDesc('created_at');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'seller_id', "id")->orderBy('created_at', 'desc');
    }
}
