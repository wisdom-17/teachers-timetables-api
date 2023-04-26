<?php

namespace App\Services;

use Wonde\Client;

class WondeAPI
{
    public function __construct(Client $client)
    {
        $this->school = $client->school(config('services.wonde_client.school_id'));
    }

    /**
     * For the given employee id, return the employee and basic info about their classes.
     */
    private function getEmployee($employeeID)
    {
        $employee = $this->school->employees->get($employeeID, ['classes']);
        return $employee;
    }

    /**
     * Get classes with detailed data (lessons and students) for the given employee
     */
    private function getClasses($employeeObj)
    {
        $classes = [];
        foreach ($employeeObj->classes->data as $class) {
            $classes[] = $this->school->classes->get($class->id, ['lessons', 'students']);
        }
        return $classes;
    }

    /**
     * Generate the timetable.
     */
    private function generateTimetable($classes) {
        $timetable = [];

        foreach ($classes as $class) {
            foreach ($class->lessons->data as $lesson) {
                $startDateTimestamp = strtotime($lesson->start_at->date);
                $endDateTimestamp = strtotime($lesson->end_at->date);
                // group lessons by date (yyyy-mm-dd), followed by start date timestamp
                $timetable[date('Y-m-d', $startDateTimestamp)][$startDateTimestamp] = [
                    'students' => $class->students->data,
                    'end_at' => $endDateTimestamp
                ];
            }
        }
        ksort($timetable);
        return $timetable;
    }

    /**
     * Get an employee timetable for the given employee id.
     */
    public function getTimetable($employeeID)
    {
        $employee = $this->getEmployee($employeeID);
        $classes = $this->getClasses($employee);
        $timetable = $this->generateTimetable($classes);
        return $timetable;
    }

}
