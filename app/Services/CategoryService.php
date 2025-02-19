<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Arr;

class CategoryService
{
    public function create(array $data): Category
    {
        $category = Category::create($data);

        $this->syncAttributes($category, Arr::get($data, 'attributes', []));
        $this->syncServers($category, Arr::get($data, 'servers', []));

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        $this->syncAttributes($category, Arr::get($data, 'attributes', []));
        $this->syncServers($category, Arr::get($data, 'servers', []));

        return $category;
    }

    private function syncAttributes(Category $category, array $attributes): void
    {
        $category->attributes()->sync($attributes);
    }

    private function syncServers(Category $category, array $servers): void
    {
        $category->servers()->sync($servers);
    }
}
