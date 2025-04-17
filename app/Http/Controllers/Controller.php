<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pr($arrayValues) {
        echo '<pre>';
        print_r($arrayValues);
        echo'</pre>';
      }

    public function generateUniqueSlug($title, $modelClass)
    {
      $slug = Str::slug($title);
      $count = $modelClass::where('slug', $slug)->count();

      if ($count > 0) {
        $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
      }

      return $slug;
    }
}
