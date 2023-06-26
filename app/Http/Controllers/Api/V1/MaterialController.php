<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Material\Material;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;

class MaterialController extends Controller
{
    use ApiResponseHelpers;

    
    public function index()
    {
        //logic
        $materials = Material::all();
        
        return $this->respondWithSuccess($materials);
    }
}
