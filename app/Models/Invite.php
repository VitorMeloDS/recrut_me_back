<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invite extends Model {
    use HasFactory;

    protected $table = 'invite';

    protected $fillable = ['email', 'token', 'status', 'expires_at'];

    public function isExpired() {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}