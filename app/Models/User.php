<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'frozen_balance',
        'last_activity_at'
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
        'last_activity_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function buyerDeals(): HasMany
    {
        return $this->hasMany(Deal::class, 'buyer_id', "id")->with('offer.seller')->orderBy('created_at', 'desc');
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

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)->withPivot('unread_count');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeOffers()
    {
        return $this->offers()->where('is_active', true);
    }

    public function ratings()
    {
        return Rating::whereHas('deal.offer', function ($query) {
            $query->where('seller_id', $this->id);
        })
            ->orderBy('created_at', 'desc')
            ->with(['deal', 'user'])
            ->get();
    }

    public function isOnline():bool
    {
        return $this->last_activity_at > now()->subMinutes(5);
    }
}
