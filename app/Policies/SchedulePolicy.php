<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role=="admin" || $user->role=="patient"||$user->role=="doctor";
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        if($user->role=="admin" || $user->role=="patient")
        {
           return true;
        }
        elseif($user->role=="doctor" && $schedule->doctor->user->id === $user->id)
        {
           return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
      return $user->role=="admin" || $user->role=="doctor";
      
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        if($user->role=="admin")
        {
           return true;
        }
        elseif($user->role=="doctor" && $schedule->doctor->user->id === $user->id)
        {
           return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        if($user->role=="admin")
        {
           return true;
        }
        elseif($user->role=="doctor" && $schedule->doctor->user->id === $user->id)
        {
           return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return true;
    }
}
