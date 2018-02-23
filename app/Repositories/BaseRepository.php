<?php
namespace App\Repositories;

use Illuminate\Support\Str;

class BaseRepository
{
    public function nextID()
    {
        return Str::random(32);
    }
}