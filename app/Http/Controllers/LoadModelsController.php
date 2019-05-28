<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

class LoadModelsController extends Controller
{
    //
    protected $base_url;
    public function __construct(UrlGenerator $url)
    {
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }
    public function LoadModels()
    {
        return response()->json([
            "success"=>true,
            "url"=>$this->base_url."/js/models",
           ],200);    
    }
}
