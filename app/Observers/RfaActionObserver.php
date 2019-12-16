<?php

namespace App\Observers;

use App\Notifications\DataChangeEmailNotification;
use App\Rfa;
use Illuminate\Support\Facades\Notification;

class RfaActionObserver
{
    public function created(Rfa $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Rfa'];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Rfa $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Rfa'];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function deleting(Rfa $model)
    {
        $data  = ['action' => 'deleted', 'model_name' => 'Rfa'];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
