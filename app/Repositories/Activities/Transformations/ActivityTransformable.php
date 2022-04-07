<?php


namespace App\Repositories\Activities\Transformations;

use App\Mail\SendEmailActivityAssigned;
use App\Repositories\Activities\Activity;
use App\Repositories\Settings\Repository\SetupRepo;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait ActivityTransformable
{

    public function transformActivity(Activity $activity)
    {
        return [
            'id'              => $activity->id,
            'customer_id'     => $activity->customer_id,
            'user_id'         => $activity->user_id,
            'name'            => $activity->name,
            'time_estimate'   => $activity->estimatedTime(),
            'start_date'      => $activity->start_date,
            'due_date'        => $activity->due_date,
            'deadline'        => $activity->deadline,
            'is_priority'     => $activity->is_priority,
            'tag_id'          => $activity->tag_id ?? '',
            'previous_ids'    => $activity->dependenceIDS(),
            'description'     => $activity->description,
            'canUpdate'       => $activity->canUpdate(),
            'canApprove'      => $activity->canApprove(),
            'canReverse'      => $activity->canReverse(),
            'canDestroy'      => $activity->canDestroy()
        ];
    }

    public function transformActivityGeneral(Activity $activity): array //TODO USERID BY userId
    {
        return [
            'id'                    => $activity->id,
            'isPlanned'             => $activity->isPlanned(),
            'customerID'            => $activity->customer->id,//old
            'customerId'            => $activity->customer->id,//new
            'customerName'          => $activity->customer->name,
            'customerNameShort'     => Str::limit($activity->customer->name,15),
            'userID'                => $activity->user_id,// old
            'userId'                => $activity->user_id, //new
            'userName'              => $activity->user->full_name,
            'userNameShort'         => Str::limit($activity->user->full_name,15),
            'activityName'          => $activity->name,
            'activityNameShort'     => Str::limit($activity->name,55),
            'startDateShort'        => Carbon::parse($activity->start_date)->format('d/m'),
            'startDateMonth'        => Carbon::parse($activity->start_date)->format('Y-m'), //filter last months(agroup)
            'dueDateShort'          => Carbon::parse($activity->due_date)->format('d/m'),
            'completedDateShort'    => $activity->completed_date ? Carbon::parse($activity->completed_date)->format('d/m') : '',
            'recordDateShort'       => $activity->completed_date_manual ? Carbon::parse($activity->completed_date_manual)->format('d/m') : '',
            'changeDateBy'          => $activity->approved_change_date_by ?? '',
            'status'                => $activity->currentStatus(),
            'statusColorBtnOutline' => _colorBtnOutline($activity->currentStatus()),
            'statusName'            => $activity->statusName(),
            'duration'              => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']),//old
            'estimatedTime'         => $activity->estimatedTime(),
            'realTime'              => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']), //new
            'startDate'             => Carbon::parse($activity->start_date)->format('Y-m-d'),
            'dueDate'               => Carbon::parse($activity->due_date)->format('Y-m-d'),
            'isPriority'            => (bool) $activity->is_priority,
            'qtySubActivities'      => $activity->sub_activities->count(),
            'canCompleted'          => $activity->canCompleted(),
            'canDestroy'            => $activity->canDestroy(),
            'isDependenciesCompleted' => $activity->isDependenciesCompleted(),
            'qtyDependencies'       => count($activity->dependenceIDS()),
            'tagId'                 => $activity->tag->id,
            'tagName'               => $activity->tag->name,
            'tagColor'              => $activity->tag->color,
            'diff_completed_date'   => (bool) $activity->different_completed_date,
            'is_approved_change_date' => (bool) $activity->is_approved_change_date,

        ];
    }

    public function notifyAssignment(Activity $activity)
    {
        $setupRepo = resolve(SetupRepo::class);

        if ($setupRepo->notifyActivityAssignment()) {
            _addHistory(UserHistory::NOTIFY,"Notificó la asignación de la actividad  $activity->name",$activity);
            Mail::to($activity->user->email)->send(new SendEmailActivityAssigned($activity,$setupRepo->findSetup()->toArray()));
        }
    }

}
