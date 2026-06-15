<?php

namespace App\Policies;

use App\Models\InventoryTransaction;
use App\Models\User;

class InventoryPolicy
{
    public function viewAny(User $user): bool    { return true; }
    public function manageStock(User $user): bool { return $user->isStaff(); }
}