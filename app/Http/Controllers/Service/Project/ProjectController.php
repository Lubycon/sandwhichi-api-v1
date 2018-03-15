<?php

namespace App\Http\Controllers\Service\Project;

// Global
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

class ProjectController extends Controller
{
    public $user;
    public $project;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @SWG\Post(
     *   path="/projects",
     *   summary="project create",
     *   operationId="project",
     *   tags={"/Projects"},
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Create Project",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/project/create")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function create(ProjectCreateRequest $request){
        $this->project = Project::create([
            "title" => $request->title,
            "description" => json_encode($request->description),
            "profile_image_url" => $request->profileImageUrl,
            "started_at" => $request->startedAt,
            "ends_at" => $request->endsAt,
            "location_id" => $request->locationId,
            "schedule_id" => Schedule::create([
                "monday" => $request->schedule['monday'],
                "tuesday" => $request->schedule['tuesday'],
                "wednesday" => $request->schedule['wednesday'],
                "thursday" => $request->schedule['thursday'],
                "friday" => $request->schedule['friday'],
                "saturday" => $request->schedule['saturday'],
                "sunday" => $request->schedule['sunday'],
                "is_negotiable" => $request->schedule['isNegotiable'],
                "schedule_recurring_id" => $request->schedule['scheduleRecurringId'],
                "start_time" => $request->schedule['startTime'],
                "end_time" => $request->schedule['endTime'],
            ])->id,
        ]);

        foreach($request->contacts as $raw_contact){
            $contact = Contact::create([
                'type_id' => $raw_contact['typeId'],
                'information' => $raw_contact['information'],
            ]);
            $this->project->contactList()->create(["contact_id" => $contact->id]);
        }


        if($request->media){
            foreach($request->media as $raw_media){
                $media = Media::create([
                    'type_id' => $raw_media['typeId'],
                    'url' => $raw_media['url'],
                ]);
                $this->project->mediaList()->create(["media_id" => $media->id]);
            }
        }

        return response()->success($this->project);
    }
}
