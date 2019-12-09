<?php

namespace Modules\Posts\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Posts\Services\PostsService;

class PostsController extends Controller
{
    /**
     * @var \Modules\Posts\Services\PostsService
     */
    private $postsService;

    public function __construct(PostsService $postsService)
    {
        $this->postsService = $postsService;
    }

    public function getPosts()
    {
        try {
            $data = $this->postsService->getPosts();

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
