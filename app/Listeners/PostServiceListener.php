<?php

namespace App\Listeners;

use App\Facade\RabbitMq;
use App\Models\Post;
use Exception;

class PostServiceListener
{
    /**
     * @throws Exception
     */
    public function listen(): void
    {
        echo "start listen post :\n";
        RabbitMq::listen('get_user_posts' , function ($data) {
            $userId = $data['user_id'];
            return Post::query()->where('user_id', $userId)->get();
        });
    }
}
