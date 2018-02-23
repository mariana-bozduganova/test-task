<?php
namespace App\Repositories;

use App\Subscriber;

class SubscribersRepository extends BaseRepository
{
    public function findByEmail($email)
    {
        return Subscriber::withEmail($email)->first();
    }

    public function save(Subscriber $subscriber)
    {
        $subscriber->save();
    }
}