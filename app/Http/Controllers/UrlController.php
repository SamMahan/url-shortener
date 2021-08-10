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

    public function appPage(Request $request) {
        return view('pages.app');
    }
    /**
     * redirects our request to the desired site based on the given minified hash key
     * @param Request $request
     * @param string $hashKey the mini-url key
     * 
     */
    public function redirect(Request $request, string $hashKey = '') {
        
        $urlObj = UrlHash::hasHashKey($hashKey)->first();
       
        if ($urlObj) {
            $urlObj->incrementTimesAccessed();
            $urlObj->save();
            return redirect()->away($urlObj->url);
        } else {
            return response(null, 404);
        }
    }

    public function save(UrlHashRequest $request) {
        $validatedData = $request->validated();
        $urlObj = UrlHash::firstOrCreate(
            ['url' => $validatedData['url']]
        );
        // $urlObj->url = $validatedData['url'];
        // $urlObj->save();
        return response(new UrlHashResource($urlObj), 200);
    }

    public function getSorted(Request $request) {
        $sortedUrls = UrlHash::sortedByViews()->limit(100)->get();
        return UrlHashResource::collection($sortedUrls);
    }
}
