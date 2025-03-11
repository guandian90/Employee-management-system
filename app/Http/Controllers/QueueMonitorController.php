<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;

class QueueMonitorController extends Controller
{
    public function index()
    {
        $queuedJobs = Queue::size('default');
        $failedJobs = DB::table('failed_jobs')->get();

        return view('queue.monitor', [
            'queued' => $queuedJobs,
            'failed' => $failedJobs,
        ]);
    }
}
