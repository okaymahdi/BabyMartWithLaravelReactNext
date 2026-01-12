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
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'addresses' => 'array',
            'wish_list' => 'array',
            'cart' => 'array',
        ];
    }

     public function comparePassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function wishList()
    {
        return $this->hasMany(WishListModel::class);
    }

    public function cart()
    {
        return $this->hasMany(CartModel::class);
    }


   protected static function booted(): void
{
    static::saving(function ($user) {

        // --------------------------
        // নাম, username, email trim & lowercase
        // --------------------------
        $user->name = trim($user->name);
        $user->username = trim($user->username ?? strtolower(preg_replace('/\s+/', '_', $user->name)));
        $user->email = strtolower(trim($user->email));

        // --------------------------
        // পাসওয়ার্ড হ্যাশ
        // --------------------------
        if ($user->isDirty('password')) {
            $user->password = Hash::make($user->password);
        }

        // --------------------------
        // ডিফল্ট avatar
        // --------------------------
        if (!$user->avatar) {
            $user->avatar = match ($user->gender) {
                'male' => 'https://example.com/avatars/male.png',
                'female' => 'https://example.com/avatars/female.png',
                default => 'https://example.com/avatars/default.png',
            };
        }

        // --------------------------
        // addresses safe handling (optional)
        // --------------------------
        if (is_array($user->addresses)) {
            $addresses = $user->addresses; // copy

            $hasDefault = false;
            foreach ($addresses as &$addr) {
                $addr['street'] = $addr['street'] ?? '';
                $addr['city'] = $addr['city'] ?? '';
                $addr['state'] = $addr['state'] ?? '';
                $addr['country'] = $addr['country'] ?? '';
                $addr['postalCode'] = $addr['postalCode'] ?? '';
                $addr['isDefault'] = !empty($addr['isDefault']) ? true : false;

                if ($addr['isDefault'] && !$hasDefault) {
                    $hasDefault = true;
                } else {
                    $addr['isDefault'] = false;
                }
            }

            if (!$hasDefault && count($addresses) > 0) {
                $addresses[0]['isDefault'] = true;
            }

            $user->addresses = $addresses; // set back
        }

        // --------------------------
        // wish_list এবং cart default empty array (optional)
        // --------------------------
        if (!is_array($user->wish_list)) {
            $user->wish_list = [];
        }
        if (!is_array($user->cart)) {
            $user->cart = [];
        }
    });
}

}
