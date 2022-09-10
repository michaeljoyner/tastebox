<?php

namespace App;

use App\Memberships\MemberProfile;
use App\Orders\DiscountCodeInfo;
use App\Orders\Menu;
use App\Purchases\MemberDiscount;
use App\Purchases\MemberShoppingBasket;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeMembers(Builder $query)
    {
        $query->where('is_admin', false);
    }

    public function isMember(): bool
    {
        return !$this->is_admin;
    }

    public static function addAdmin($options)
    {
        return static::create(array_merge($options, ['is_admin' => true]));
    }

    public function shoppingBasket(): HasOne
    {
        return $this->hasOne(MemberShoppingBasket::class);
    }

    public function resetPassword(string $password)
    {
        $this->password = Hash::make($password);
        $this->save();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function upcomingKits(): Collection
    {
        return OrderedKit::whereHas(
            'order',
            fn(Builder $query) => $query->where('orders.user_id', $this->id)
                                        ->where('is_paid', true)
        )
                         ->where('delivery_date', '>=', now()->startOfDay())
                        ->whereNot('status', OrderedKit::STATUS_CANCELED)
                         ->get();
    }

    public function hasPlacedOrderForNextMenu(): bool
    {
        $menu = Menu::nextAvailable();

        return !!OrderedKit::whereHas(
            'order',
            fn(Builder $query) => $query->where('orders.user_id', $this->id)
                                        ->where('is_paid', true)
        )
                           ->where('menu_id', $menu->id)
                           ->where('delivery_date', '>=', now()->startOfDay())->count();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function initiateProfile()
    {
        $split_name = explode(' ', $this->name);
        $first_name = array_shift($split_name);
        $last_name = implode(' ', $split_name);
        $this->profile()->create([
            'first_name'       => $first_name,
            'last_name'        => $last_name,
            'email'            => $this->email,
            'phone'            => '',
            'address_line_one' => '',
            'address_line_two' => '',
            'address_city'     => '',
        ]);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(MemberDiscount::class);
    }

    public function awardDiscount(DiscountCodeInfo $codeInfo, $tag = null): MemberDiscount
    {
        return $this
            ->discounts()
            ->create(array_merge($codeInfo->forMember(), ['discount_tag' => $tag]));
    }
}
