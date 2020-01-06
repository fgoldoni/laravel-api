<?php

/**
 * Created by PhpStorm.
 * Role: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Categories\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Modules\Categories\Entities\Category;
use Modules\Categories\Repositories\Contracts\CategoriesRepository;

/**
 * Class EloquentPostsRepository.
 */
class EloquentCategoriesRepository extends RepositoryAbstract implements CategoriesRepository
{
    public function model()
    {
        return Category::class;
    }

    public function getSiblingsCategories(int $key, $model)
    {
        $parent = $this->resolveModel()->newQuery()->where('slug', 'categories')->with(['descendants'])->whereIsRoot()->get()->toTree()->first();

        return $parent->descendants->map(function ($category) use ($model) {
            return [
                'id'    => $category->id,
                'name'  => $category->getTranslation('name', session()->get('locale')),
                'slug'  => $category->slug,
                'count' => $category->entries($model)->count()
            ];
        });
    }

    public function siblings(string $slug, string $model = null)
    {
        $parent = $this->resolveModel()->newQuery()->where('slug', $slug)->with(['descendants'])->whereIsRoot()->get()->toTree()->first();

        if (null === $parent) {
            return [];
        }

        return $parent->descendants->map(function ($category) use ($model) {
            $locale = session()->has('locale') ? session()->get('locale') : 'en';

            return [
                'id'    => $category->id,
                'name'  => $category->getTranslation('name', $locale),
                'slug'  => $category->slug,
            ];
        });
    }
}
