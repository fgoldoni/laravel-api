<?php

namespace Modules\Posts\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Posts\Http\Requests\StorePostRequest;
use Modules\Posts\Http\Requests\UpdatePostRequest;
use Modules\Posts\Services\PostsService;
use Modules\Posts\Transformers\PostCollection;
use Modules\Posts\Transformers\PostsCollection;

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
            $data = PostsCollection::collection($this->postsService->getPosts());

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function edit(int $id)
    {
        try {
            $result['post'] = new PostCollection($this->postsService->find($id));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->postsService->storePost($request->only('name', 'content', 'online', 'user_id'), $request->get('categories'), $request->get('tags'));
            $result['data'] = new PostCollection($post);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(UpdatePostRequest $request, int $id)
    {
        try {
            $post = $this->postsService->updatePost(
                $id,
                $request->only('name', 'content', 'online', 'user_id'),
                $request->get('categories'),
                $request->get('tags')
            );

            $result['data'] = new PostCollection($post);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
