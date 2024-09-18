<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['media_type_id', 'agent_id', 'account'];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function media_type()
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }
}
