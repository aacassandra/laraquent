<?php

namespace Laraquent;

class BeautyEloquentTools
{
    public static function arr2Json($items)
    {
        $items = json_encode($items);
        $items = json_decode($items);
        return $items;
    }

    public static function ResponseTemplate()
    {
        return BeautyEloquentTools::arr2Json([
            'status' => true,
            'output' => ''
        ]);
    }
}
