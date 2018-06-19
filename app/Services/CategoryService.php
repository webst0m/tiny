<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    // todo cache
    public function getAllByType($type)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        if (!$isSuperAdmin) {
            $canAccessCategoryIDs = $user->categories->pluck('id');
        }else{
            $canAccessCategoryIDs = collect([]);
        }
        $topicCategories = Category::topCategories()->ordered()->oldest()->get();
        foreach($topicCategories as $k=>$topicCategory) {
            if(!$isSuperAdmin && !$canAccessCategoryIDs->contains($topicCategory->id)){
                $topicCategories->splice($k ,1);
            }
        }
        $topicCategories->load(['children' => function ($query) use ($type) {
            $query->byType($type)->ordered()->oldest();
        }]);
        if (!is_null($type)) {
            $topicCategories = $topicCategories->filter(function ($category) use ($type) {
                return $category->type == $type || $category->children->isNotEmpty();
            });
        }
        
        foreach($topicCategories as $topicCategory) {
            foreach ($topicCategory->children as $k=>$child){
                if(!$isSuperAdmin && !$canAccessCategoryIDs->contains($child->id)){
                    $topicCategory->children->splice($k ,1);
                } 
            }
        }

        return $topicCategories;
    }

    public function visualOutput($type = null, $indentStr = '-')
    {
        $topicCategories = $this->getAllByType($type);
        $collect = collect([]);
        foreach ($topicCategories as $topicCategory) {
            $collect->push(['indent_str' => '', 'data' => $topicCategory]);
            foreach ($topicCategory->children as $child) {
                    $collect->push(['indent_str' => $indentStr, 'data' => $child]);
            }
            
        }
        return $collect;
    }
}
