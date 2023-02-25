<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stipend extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'interval',
        'status',
        'success_billed',
        'next_billing',
        'created_at',
        'due_date',
        'limit',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function beneficiaries(){
        return $this->hasMany(Beneficiary::class);
    }
}
