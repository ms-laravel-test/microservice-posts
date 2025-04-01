<?php

namespace App\Console\Commands;

use App\Listeners\PostServiceListener;
use Illuminate\Console\Command;

class ListenToPostRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for incoming requests for user posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $listener = new PostServiceListener();
        $listener->listen();
    }

}
