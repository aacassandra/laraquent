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
}
