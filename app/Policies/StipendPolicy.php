<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stipend;
use Illuminate\Auth\Access\HandlesAuthorization;

class StipendPolicy
{
    use HandlesAuthorization;

    public function canAddBeneficiary(User $user, Stipend $stipend){
        return $user->id === $stipend->user_id;
    }
}
