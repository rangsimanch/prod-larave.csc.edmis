<?php

namespace App\Observers;

use App\Notifications\DataChangeEmailNotification;
use App\Task;
use Illuminate\Support\Facades\Notification;

class TaskActionObserver
{
    public function created(Task $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'Task'];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }

    public function updated(Task $model)
    {
        $data  = ['action' => 'updated', 'model_name' => 'Task'];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
