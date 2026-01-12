<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Model
{
    use HasApiTokens;
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

    protected $hidden = ['password'];

    protected $casts = [
        'addresses' => 'array',
        'wish_list' => 'array',
        'cart' => 'array',
    ];

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

            // trim & lowercase
            $user->name = trim($user->name);
            $user->username = trim($user->username ?? strtolower(preg_replace('/\s+/', '_', $user->name)));
            $user->email = strtolower(trim($user->email));

            // hash password
            if ($user->isDirty('password')) {
                $user->password = Hash::make($user->password);
            }

            // avatar default
            if (!$user->avatar) {
                $user->avatar = match ($user->gender) {
                    'male' => 'https://example.com/avatars/male.png',
                    'female' => 'https://example.com/avatars/female.png',
                    default => 'https://example.com/avatars/default.png',
                };
            }

            // ensure 1 default address
            if (\is_array($user->addresses)) {
                $hasDefault = false;

                foreach ($user->addresses as &$addr) {
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

                if (!$hasDefault && \count($user->addresses) > 0) {
                    $user->addresses[0]['isDefault'] = true;
                }
            }
        });
    }
}
