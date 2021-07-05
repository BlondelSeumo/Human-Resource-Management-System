<?php

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class AlterLeaveApplicationData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $leaveApplications = \App\Models\LeaveApplication::where('application_status', 'approved')->get();

        foreach($leaveApplications as $application) {

            $admin = \App\Models\Admin::where('company_id', $application->company_id)->first();

            $start = Carbon::createFromFormat("Y-m-d", $application->start_date);

            if ($application->end_date == null) {
                $end = clone $start;
            } else {
                $end = Carbon::createFromFormat("Y-m-d", $application->end_date);
            }


            $diffDays = $end->diffInDays($start);

            for ($i = 0; $i <= $diffDays; $i++) {

                $date = $start;
                $attendance = Attendance::firstOrCreate(['date' => $date->format("Y-m-d"),
                    'employee_id' => $application->employee_id]);

                $attendance->leaveType = $application->leaveType;
                $attendance->halfDayType = $application->halfDayType;
                $attendance->reason = $application->reason;
                $attendance->status = 'absent';
                $attendance->applied_on = $application->applied_on;
                $attendance->last_updated_by = $admin->id;
                $attendance->application_status = 'approved';
                $attendance->save();
                $start->addDays(1);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
