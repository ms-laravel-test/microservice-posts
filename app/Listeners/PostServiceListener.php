<?php

namespace App\Listeners;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\Post;

class PostServiceListener
{
    /**
     * @throws \Exception
     */
    public function listen(): void
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('get_user_posts', false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) use ($channel) {
            $data = json_decode($msg->body, true);
            $userId = $data['user_id'];

            $posts = Post::query()->where('user_id', $userId)->get();
            $response = json_encode($posts);

            $responseMsg = new AMQPMessage($response, [
                'correlation_id' => $msg->get('correlation_id'),
            ]);

            $channel->basic_publish($responseMsg, '', $msg->get('reply_to'));
        };

        $channel->basic_consume('get_user_posts', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
