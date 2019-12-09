<?php

namespace Modules\Categories\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Categories\Entities\Category;
use Modules\Categories\Http\Requests\StoreCategoryRequest;
use Modules\Categories\Http\Requests\UpdateCategoryRequest;
use Modules\Categories\Services\CategoriesService;
use Modules\Categories\Transformers\CategoriesCollection;

class CategoriesController extends Controller
{
    /**
     * @var \Modules\Categories\Services\CategoriesService
     */
    private $categoriesService;

    public function __construct(CategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }

    public function getCategories()
    {
        try {
            $data = CategoriesCollection::collection($this->categoriesService->getCategories());

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function children(Category $category)
    {
        try {
            $data = CategoriesCollection::collection($this->categoriesService->getChildren($category->id));

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $data = [
                'name'      => '',
                'parent_id' => null
            ];

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function append(Category $category)
    {
        try {
            $data = ['name' => '', 'parent_id' => $category->id];

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $this->categoriesService->create($request->only('name', 'parent_id'));

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function edit(int $id)
    {
        try {
            $data = $this->categoriesService->find($id, ['id', 'name', 'slug', 'parent_id']);

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $data = $this->categoriesService->update($category->id, $request->only('name'));

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
