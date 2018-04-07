<?php

namespace App\Http\Controllers\Service\Keyword;

// Global
use App\Models\Ability;
use App\Models\Contact;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\ProjectContact;
use App\Models\ProjectMedia;
use App\Models\Schedule;
use Log;
use Auth;
use Abort;

// Models
use App\Models\Project;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Requests
use App\Http\Requests\Service\Project\ProjectCreateRequest;

class KeywordController extends Controller
{
    public $user;
    public $project;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @SWG\Post(
     *   path="/keywords/{keyword_name}",
     *   @SWG\Parameter(
     *     name="keyword_name",
     *     description="search keyword name, require url encode",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="Food%20shop%0D%0A",
     *   ),
     *   summary="keyword search",
     *   operationId="keyword",
     *   tags={"/Keywords"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function search(Request $request, $keyword){
        // TODO min count 개수 정의, limit 개수 정의
        $url_decode_keyword = urldecode($keyword);
        $keywords = Keyword::where('name','=',$url_decode_keyword)
            ->where('count','>',2)
            ->limit(10)
            ->get();
        return response()->success($keywords);
    }
}
