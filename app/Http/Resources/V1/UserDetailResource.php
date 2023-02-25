<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'balance' => $this->balance,
        'phone_number' => $this->phone_number,
        'state_of_residence' => $this->state_of_residence,
        'residence_address' => $this->residence_address,
        'security_question' => $this->security_question,
        'security_question_answer' => $this->security_question_answer,
        'account_number' => $this->account_number,
        'account_name' => $this->account_name,
        'bank_name' => $this->bank_name,
        ];
    }
}
