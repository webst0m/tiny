<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    // todo cache
    public function getAllByType($type)
    {
        $topicCategories = Category::topCategories()->ordered()->oldest()->get();
        $topicCategories->load(['children' => function ($query) use ($type) {
            $query->byType($type)->ordered()->oldest();
        }]);
        if (!is_null($type)) {
            $topicCategories = $topicCategories->filter(function ($category) use ($type) {
                return $category->type == $type || $category->children->isNotEmpty();
            });
        }
        return $topicCategories;
    }

    public function visualOutput($type = null, $indentStr = '-')
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        if (!$isSuperAdmin) {
            $canAccessCategoryIDs = $user->categories->pluck('id');
        }else{
            $canAccessCategoryIDs = collect([]);
        }

        $topicCategories = $this->getAllByType($type);
        $collect = collect([]);
        foreach ($topicCategories as $topicCategory) {
            if ($isSuperAdmin || $canAccessCategoryIDs->contains($topicCategory->id)) {
                $collect->push(['indent_str' => '', 'data' => $topicCategory]);
                foreach ($topicCategory->children as $child) {
                    if ($isSuperAdmin || $canAccessCategoryIDs->contains($child->id)) {
                        $collect->push(['indent_str' => $indentStr, 'data' => $child]);
                    }
                }
            }
        }
        return $collect;
    }
}
