<?php

namespace App\Models;

use App\Enums\UserType;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\Admin\Transaction;
use App\Models\Admin\Wallet;
use App\Models\CashBonu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'user_name',
        'name',
        'profile',
        'email',
        'password',
        'profile',
        'phone',
        'max_score',
        'agent_id',
        'status',
        'type',
        'is_changed_password',
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
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): belongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function scopeRoleLimited($query)
    {
        if (! Auth::user()->hasRole('Admin')) {
            return $query->where('agent_id', Auth::id());
        }

        return $query;
    }

    public function scopeChild($query, $agent_id)
    {
        return $query->where('agent_id', $agent_id)->count();
    }

    public function scopeHasRole($query, $roleId)
    {
        return $query->whereRelation('roles', 'role_id', $roleId);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public static function adminUser()
    {
        return self::where('type', UserType::Admin)->first();
    }

    public function cashBonuses()
    {
        return $this->hasMany(CashBonu::class);
    }
}
