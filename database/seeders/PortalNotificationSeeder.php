<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortalNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample notification data.
        $notifications = [
            [
                'title' => 'New Post',
                'content' => '<b>{Users.username}</b> added a new post.',
                'type' => 'announcement'
            ],
            [
                'title' => 'New Repost',
                'content' => '<b>{Users.username}</b> reposted a post.',
                'type' => 'announcement'
            ],
            [
                'title' => 'Repost Received',
                'content' => '<b>{Users.username}</b> reposted your post.',
                'type' => 'announcement'
            ],
            [
                'title' => 'Mention Received Post',
                'content' => '<b>{Users.username}</b> mentioned you on their post.',
                'type' => 'announcement'
            ],
            [
                'title' => 'Reaction Received Post',
                'content' => '<b>{Users.username}</b> reacted your post.',
                'type' => 'reaction'
            ],
            [
                'title' => 'Comment Received Post',
                'content' => '<b>{Users.username}</b> commented on your post.',
                'type' => 'comment'
            ],
            [
                'title' => 'Reaction Received Comment',
                'content' => '<b>{Users.username}</b> reacted your comment.',
                'type' => 'comment'
            ],
            [
                'title' => 'Mention Received Comment',
                'content' => '<b>{Users.username}</b> mentioned you on their comment.',
                'type' => 'comment'
            ],
            // Add more sample notifications as needed.
        ];

        // Insert the sample data into the portal_notifications table.
        DB::table('portal_notifications')->insert($notifications);
    }
}
