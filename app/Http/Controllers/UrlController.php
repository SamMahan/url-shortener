<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UrlHash;
use App\Http\Requests\UrlHashRequest;
use App\Http\Resources\UrlHashResource;

class UrlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * redirects our request to the desired site based on the given minified hash key
     * @param Request $request
     * @param string $hashKey the mini-url key
     * 
     */
    public function redirect(Request $request, string $hashKey) {
        $urlObj = UrlHash::hasHashKey($hashKey)->first();
        if ($url){
            $urlObj->incrementTimesAccessed();
            return redirect()->away($urlObj->url);
        }
    }

    public function save(UrlHashRequest $request) {
        $valdiatedData = $request->validated();
        $urlObj = new UrlHash();
        $urlObj->url = $validatedData['url'];
        $urlObj->save();
        return response(null, 200);
    }

    public function getSorted(Request $request) {
        $sortedUrls = UrlHash::sortedByViews()->limit(100)->get();
        return UrlHashResource::collection($sortedUrls);
    }
}
