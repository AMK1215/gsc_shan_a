<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function refreshBalance()
    {
        // Recalculate balance if needed, or simply reload the balance from the database
        $this->balance = $this->fresh()->balance;

        // Optionally, you could recalculate balance based on transactions
        // $this->balance = $this->transactions()->sum('amount');

        // Return the updated wallet instance
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
