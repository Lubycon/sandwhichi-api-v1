<?php

namespace App\Http\Requests\Service\Project;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class ProjectCreateRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        // TODO 회원 인증 구현시 요소 추가
        return User::isUser();
    }

    /**
     *  @SWG\Definition(
     *   definition="project/create",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"title", "description", "startedAt", "endsAt", "locationId", "schedule", "contacts"},
     *           @SWG\Property(property="title", type="string", default="Project title"),
     *           @SWG\Property(property="description", type="string", default="description"),
     *           @SWG\Property(property="profileImageUrl", type="string", default="https://s3.sandwhichi.con/sssdff/11"),
     *           @SWG\Property(property="startedAt", type="timestamp", default="2017-10-10 12:00:00"),
     *           @SWG\Property(property="endsAt", type="timestamp", default="2018-11-11 12:00:00"),
     *           @SWG\Property(property="locationId", type="string", default="1"),
     *           @SWG\Property(property="schedule", type="object",
     *               @SWG\Property(property="monday", type="boolean", default=true),
     *               @SWG\Property(property="tuesday", type="boolean", default=true),
     *               @SWG\Property(property="wednesday", type="boolean", default=true),
     *               @SWG\Property(property="thursday", type="boolean", default=true),
     *               @SWG\Property(property="saturday", type="boolean", default=true),
     *               @SWG\Property(property="friday", type="boolean", default=true),
     *               @SWG\Property(property="sunday", type="boolean", default=true),
     *               @SWG\Property(property="isNegotiable", type="boolean", default=true),
     *               @SWG\Property(property="scheduleRecurringId", type="boolean", default="1"),
     *               @SWG\Property(property="startTime", type="time", default="01:00:00"),
     *               @SWG\Property(property="endTime", type="time", default="03:00:00"),
     *           ),
     *           @SWG\Property(property="contacts", type="array",
     *                @SWG\Items(
     *                    type="object",
     *                    @SWG\Property(property="typeId", type="string", default="1"),
     *                    @SWG\Property(property="information", type="string", default="daniel@good.com"),
     *                ),
     *           ),
     *           @SWG\Property(property="keywords", type="array", default = {"IT", "Food"},
     *                @SWG\Items(
     *                    type = "string"
     *                ),
     *           ),
     *           @SWG\Property(property="keywords", type="array", default = {"PHP", "Javascript"},
     *                @SWG\Items(
     *                    type = "string"
     *                ),
     *           ),
     *           @SWG\Property(property="media", type="array",
     *                @SWG\Items(
     *                    type="object",
     *                    @SWG\Property(property="typeId", type="string", default="1"),
     *                    @SWG\Property(property="url", type="string", default="https://youtube.com/123"),
     *                ),
     *           ),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            // 프로젝트 관련
            "title" => "required|min:2|max:100",
            "description" => "required|min:2",
            "profileImageUrl" => "required", // TODO check is our host
            "startedAt" => "required|date",
            "endsAt" => "required|date|after:start_date",
            "locationId" => "required|exists:locations,id",

            // 프로젝트 스케쥴 관련
            "schedule" => "required|array",
            "schedule.monday" => "required|boolean",
            "schedule.tuesday" => "required|boolean",
            "schedule.wednesday" => "required|boolean",
            "schedule.thursday" => "required|boolean",
            "schedule.friday" => "required|boolean",
            "schedule.saturday" => "required|boolean",
            "schedule.sunday" => "required|boolean",
            "schedule.isNegotiable" => "required|boolean",
            "schedule.scheduleRecurringId" => "required|exists:schedule_recurring_types,id",
            "schedule.startTime" => "required|date_format:H:i:s",
            "schedule.endTime" => "required|date_format:H:i:s",

            // 연락처 관련
            "contacts" => "required|array",
            "contacts.*.typeId" => "required|exists:contact_types,id",
            "contacts.*.information" => "required|max:100",

            // 키워드 관련
            "keywords" => "required|array",

            // 어빌리티 관련
            "abilities" => "required|array",

            // 미디어 관련
            "media" => "array",
            "media.*.typeId" => "exists:media_types,id",
            "media.*.url" => "url", // TODO check if image, that our hosts
        ];
        return $requiredRule;
    }
}
