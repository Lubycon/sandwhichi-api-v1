<?php

namespace App\Http\Controllers\Service\Ability;

// Global
use App\Models\Ability;
use App\Models\Contact;
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

class AbilityController extends Controller
{
    public $user;
    public $project;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @SWG\Post(
     *   path="/abilities/{Ability_name}",
     *   @SWG\Parameter(
     *     name="Ability_name",
     *     description="search Ability name, require url encode",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="C%2B%2B",
     *   ),
     *   summary="Ability search",
     *   operationId="Ability",
     *   tags={"/Abilities"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function search(Request $request, $Ability){
        // TODO min count 개수 정의, limit 개수 정의
        $url_decode_Ability = urldecode($Ability);
        $abilities = Ability::where('name','=',$url_decode_Ability)
            ->where('count','>',2)
            ->limit(10)
            ->get();
        return response()->success($abilities);
    }
}
