<?php

namespace App\Services;


use App\Models\Banner;
use App\Models\Category;
use App\Models\Link;
use Illuminate\Support\Collection;

class CustomOrder
{
    public static $modelMapping = [
        'banner' => Banner::class,
        'link' => Link::class,
        'category' => Category::class
    ];

    public function order(Collection $collection)
    {
        $indexOrder = setting($this->getSettingKey(get_class($collection->first())));
        if (empty($indexOrder)) {
            return $collection;
        }
        $indexOrder = json_decode($indexOrder, true);
        $count = count($indexOrder);
        return $collection->sortBy(function ($item) use ($indexOrder, $count) {
            $id = $item->getKey();
            if (array_key_exists($id, $indexOrder)) {
                return $indexOrder[$id];
            } else {
                return $count;
            }
        });
    }

    public function setOrder(array $indexOrder, $model)
    {
        $indexOrder = array_flip(array_values(array_map(function ($id) {
            return intval($id);
        }, $indexOrder)));
        $key = $this->getSettingKey(static::$modelMapping[$model]);
        setting([
            $key => [
                'value' => json_encode($indexOrder),
                'is_system' => true,
                'type_id' => 0
            ]
        ]);
    }

    protected function getSettingKey($fullModelName)
    {
        return $fullModelName . ':order';
    }
}
