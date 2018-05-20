<?php

namespace App\Http\Controllers\Service\Project;

// Global
use App\Http\Requests\Service\Project\ProjectUpdateRequest;
use App\Models\Ability;
use App\Models\Contact;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\ProjectAbility;
use App\Models\ProjectContact;
use App\Models\ProjectKeyword;
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
     *   @SWG\Parameter(
     *     type="string",
     *     name="Authorization",
     *     in="header",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *     required=true
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function create(ProjectCreateRequest $request){
        $this->project = Project::create([
            "title" => $request->title,
            "description" => json_encode($request->description),
            "profile_image_url" => Media::S3RawImageParse($request->profileImageUrl),
            "started_at" => $request->startedAt,
            "ends_at" => $request->endsAt,
            "location_id" => $request->locationId,
            "schedule_id" => Schedule::firstOrCreate([
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

        ProjectContact::ProjectHardSync($this->project, $request->contacts);
        ProjectMedia::ProjectHardSync($this->project, $request->media);

        ProjectKeyword::ProjectSoftSync($this->project, $request->keywords);
        ProjectAbility::ProjectSoftSync($this->project, $request->abilities);

        $this->project->fresh(['contactList','keywordList','abilityList','mediaList','schedule']);
        return response()->success($this->project);
    }

    /**
     * @SWG\Put(
     *   path="/projects/{project_id}",
     *   @SWG\Parameter(
     *     name="project_id",
     *     description="Project id",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="1",
     *   ),
     *   summary="project update",
     *   operationId="project",
     *   tags={"/Projects"},
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Update Project",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/project/update")
     *   ),
     *   @SWG\Parameter(
     *     type="string",
     *     name="Authorization",
     *     in="header",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *     required=true
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function update(ProjectUpdateRequest $request, $project_id){
        $this->project = Project::with([
            'contactList','keywordList','abilityList','mediaList','schedule'
        ])->findOrFail($project_id);

        // TODO remove before update image

        $this->project->update([
            "title" => $request->title,
            "description" => json_encode($request->description),
            "profile_image_url" => Media::S3RawImageParse($request->profileImageUrl),
            "started_at" => $request->startedAt,
            "ends_at" => $request->endsAt,
            "location_id" => $request->locationId,
            "meeting_address" => $request->meetingAddress,
            "schedule_id" => Schedule::firstOrCreate([
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

        ProjectContact::ProjectHardSync($this->project, $request->contacts);
        ProjectMedia::ProjectHardSync($this->project, $request->media);

        ProjectKeyword::ProjectSoftSync($this->project, $request->keywords);
        ProjectAbility::ProjectSoftSync($this->project, $request->abilities);

        $this->project->fresh(['contactList','keywordList','abilityList','mediaList','schedule']);
        return response()->success($this->project);
    }
}
