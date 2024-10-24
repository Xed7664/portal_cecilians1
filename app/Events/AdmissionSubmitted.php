<?php

// app/Events/AdmissionApproved.php
namespace App\Events;

use App\Models\Admission;
use Illuminate\Queue\SerializesModels;

class AdmissionApproved
{
    use SerializesModels;

    public $admission;

    public function __construct(Admission $admission)
    {
        $this->admission = $admission;
    }
}

