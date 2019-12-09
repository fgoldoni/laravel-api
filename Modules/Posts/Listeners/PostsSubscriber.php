<?php

namespace Modules\Posts\Listeners;

use App\Flag;
use App\User;
use Modules\Posts\Entities\Post;

class PostsSubscriber
{
    /**
     * @var \Modules\Posts\Entities\Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: ' . Post::class, [$this, 'handlePostCreated']);
        $events->listen('eloquent.deleting: ' . Post::class, [$this, 'handlePostDeleted']);
        $events->listen('eloquent.restored: ' . Post::class, [$this, 'handleUserRestored']);
    }

    public function handlePostCreated(Post $post)
    {
//        $users = $this->getUserByRole(Flag::ROLE_ADMIN);
//
//        Notification::send($users, new UserCreated($user));
    }

    public function handlePostDeleted(Post $post)
    {
//        $users = $this->getUserByRole(Flag::ROLE_ADMIN);
//
//        Notification::send($users, new UserCreated($user));
    }

    public function handleUserRestored(Post $post)
    {
//        $users = $this->getUserByRole(Flag::ROLE_ADMIN);
//
//        Notification::send($users, new UserCreated($user));
    }

    public function getUserByRole(string $role)
    {
        return User::role($role)->get();
    }
}
