<?php

namespace App\Http\Controllers;

use App\Services\WondeAPI;

class TimetableController extends Controller
{
    protected $wondeAPI;

    public function __construct(WondeAPI $wondeAPI)
    {
        $this->wondeAPI = $wondeAPI;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $employeeID)
    {
        return response()->json(
            ['timetable' => $this->wondeAPI->getTimetable($employeeID)]
        , 200);
    }
}
