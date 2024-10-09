<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GradeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subjectEnrolledId;
    public $studentId;
    public $grade;

    public function __construct($subjectEnrolledId, $studentId, $grade)
    {
        $this->subjectEnrolledId = $subjectEnrolledId;
        $this->studentId = $studentId;
        $this->grade = $grade;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('grades.' . $this->subjectEnrolledId);
    }

    public function broadcastWith()
    {
        return [
            'subjectEnrolledId' => $this->subjectEnrolledId,
            'studentId' => $this->studentId,
            'grade' => $this->grade,
        ];
    }
}
