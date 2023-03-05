<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_country_code',
        'phone_number',
        'state_of_residence',
        'residence_address', 
        'security_question',
        'security_question_answer',
        'account_number',
        'account_name',
        'bank_name'
    ];

    protected $hidden = [
        'phone_country_code',
        'phone_number',
        'security_question',
        'security_question_answer',
        'account_number',
        'account_name',
        'bank_name'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
