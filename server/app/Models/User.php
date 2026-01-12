<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property string $role
 * @property string $gender
 * @property array $addresses
 * @property array $wish_list
 * @property array $cart
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    // 📝 Mass assignable fields
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'role',
        'gender',
        'addresses',
        'wish_list',
        'cart',
    ];

    // 🔒 Hidden fields for JSON response
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🛠 Casts: auto convert json/array/datetime
    protected $casts = [
        'addresses' => 'array',    // 🏠 addresses JSON → array
        'wish_list' => 'array',    // ❤️ wish_list JSON → array
        'cart' => 'array',         // 🛒 cart JSON → array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 🔑 Password comparison method
    public function comparePassword($enteredPassword)
    {
        return Hash::check($enteredPassword, $this->password);
    }

    // 🚀 Model hooks: create defaults, hash password
    protected static function booted()
    {
        static::creating(function ($user) {

            // --------------------------
            // ✏️ Name & username normalization
            // --------------------------
            if ($user->name) {
                $user->name = trim($user->name);
            }

            // 🆔 Username auto-generate if empty
            if (!$user->username && $user->name) {
                $baseUsername = strtolower(preg_replace('/\s+/', '_', trim($user->name)));
                $username = $baseUsername;
                $count = 1;

                while (self::where('username', $username)->exists()) {
                    $username = $baseUsername . '_' . $count;
                    $count++;
                }

                $user->username = $username; // ✅ final username
            }

            // ✉️ Email lowercase
            if ($user->email) {
                $user->email = strtolower(trim($user->email));
            }

            // --------------------------
            // 🔐 Password hashing
            // --------------------------
            if ($user->isDirty('password')) {
                $user->password = Hash::make($user->password);
            }

            // --------------------------
            // 🖼 Default avatar
            // --------------------------
            if (!$user->avatar) {
                $user->avatar = match ($user->gender) {
                    'male' => 'https://plus.unsplash.com/premium_photo-1664536392779-049ba8fde933?w=600',
                    'female' => 'https://plus.unsplash.com/premium_photo-1670884441012-c5cf195c062a?w=600',
                    default => 'https://images.unsplash.com/photo-1728577740843-5f29c7586afe?w=600',
                };
            }

            // --------------------------
            // 🏠 Addresses handling
            // --------------------------
            if (is_array($user->addresses) && count($user->addresses) > 0) {
                $addresses = $user->addresses;
                $hasDefault = false;

                foreach ($addresses as &$address) {
                    // ✅ ensure boolean
                    $address['isDefault'] = !empty($address['isDefault']);

                    if ($address['isDefault'] && !$hasDefault) {
                        $hasDefault = true; // ✔️ first default
                    } else {
                        $address['isDefault'] = false; // ❌ rest default false
                    }
                }

                // 👑 If no default, first address is default
                if (!$hasDefault) {
                    $addresses[0]['isDefault'] = true;
                }

                $user->addresses = $addresses;
            } else {
                $user->addresses = []; // 🏠 empty array if none
            }

            // --------------------------
            // ❤️ Wish list & 🛒 Cart defaults
            // --------------------------
            $user->wish_list = is_array($user->wish_list) ? $user->wish_list : [];
            $user->cart = is_array($user->cart) ? $user->cart : [];
        });
    }

    // 🔗 Relationships (optional)
    public function wishList()
    {
        return $this->hasMany(WishListModel::class);
    }

    public function cart()
    {
        return $this->hasMany(CartModel::class);
    }
}
