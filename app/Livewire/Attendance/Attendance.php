<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\GlobalFilter;
use App\Models\UserAttendance;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Attendance extends Component
{
    use LivewireAlert;
    
    public $date;
    public $startDate;
    public $endDate;
    public $attendanceRecords = [];
    public $isAdmin = false; // Flag to check if the user is admin or super admin
    public $canTimeIn = true;
    public $canTimeOut = false;
    public $search = ''; 
    public $attendanceId;
    

    public function mount()
    {
        $filter = GlobalFilter::where('filter_name', 'attendance_date_range')->first();
        $this->startDate = $filter->start_date ?? now()->subYear()->format('Y-m-d'); // Default: one year ago
        $this->endDate = $filter->end_date ?? now()->format('Y-m-d'); // Default: today

        // Check if the user is admin or super admin
        $this->isAdmin = in_array(Auth::user()->role, ['admin', 'super_admin']);

        $this->filterAttendanceRecords();
        $this->checkLastAttendance();
    }
    
    public function checkLastAttendance()
    {
        $lastAttendance = UserAttendance::where('user_id', Auth::id())
            ->latest('id')
            ->first();

        if ($lastAttendance) {
            $this->canTimeIn = $lastAttendance->time_out !== null;
            $this->canTimeOut = $lastAttendance->time_in !== null && $lastAttendance->time_out === null;
        } else {
            $this->canTimeIn = true;
            $this->canTimeOut = false;
        }
    }

    public function timeIn()
    {
        // Create a new record for every time-in
        UserAttendance::create([
            'user_id' => Auth::id(),
            'date' => now()->format('Y-m-d'),
            'time_in' => now()->format('H:i:s'),
            'time_out' => null,
            'seconds_elapsed' => null,
        ]);
        $this->checkLastAttendance();
        $this->filterAttendanceRecords(); // Refresh the list
    }

    public function timeOut()
    {
        $attendance = UserAttendance::where('user_id', Auth::id())
            ->whereNull('time_out')
            ->latest('id')
            ->first();

        if ($attendance) {
            $timeIn = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->time_in);
            $timeOut = now();

            $attendance->time_out = $timeOut->format('H:i:s');
            $attendance->seconds_elapsed = $timeIn->diffInSeconds($timeOut); // Calculate elapsed seconds
            $attendance->save();
            $this->checkLastAttendance();
            $this->filterAttendanceRecords(); // Refresh the list
        }
    }

    public function updateAttendance($attendanceId, $field, $value)
    {
        if ($this->isAdmin) {
            $attendance = UserAttendance::find($attendanceId);

            if ($attendance) {
                $attendance->$field = $value;

                // If updating time_out, calculate seconds_elapsed
                if ($field === 'time_out' && $attendance->time_in) {
                    $timeIn = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->time_in);
                    $timeOut = \Carbon\Carbon::createFromFormat('H:i:s', $value, now()->timezone())->format('H:i:s');
                    if (substr_count($value, ':') === 1) {
                        $timeOut = \Carbon\Carbon::createFromFormat('H:i', $value, now()->timezone())->format('H:i:s');
                    }
                    $attendance->seconds_elapsed = $timeIn->diffInSeconds($timeOut);
                }

                $attendance->save();
                $this->filterAttendanceRecords(); // Refresh the list
                $this->checkLastAttendance();
            }
        }
    }

    public function filterAttendanceRecords()
    {
        if ($this->isAdmin) {
            // Admins can view all attendance records
            $query = UserAttendance::whereBetween('date', [$this->startDate, $this->endDate])
                ->with('user'); // Load user relationship

            if (!empty($this->search)) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            }

            $this->attendanceRecords = $query->get()
                ->sortBy(function ($record) {
                    return $record->user->name ?? ''; // Sort by user name
                });
        } else {
            // Regular users can only view their own attendance records
            $this->attendanceRecords = UserAttendance::where('user_id', Auth::id())
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->get();
        }
    }

    public function deleteRecord($id)
    {
        $record = UserAttendance::find($id);
        if ($record) {
            $record->delete();
            
            $this->alert('warning', 'Attendance Deleted Successfully', [
                'position' => 'top-right',
                'timer' => 5000,
            ]);

            $this->filterAttendanceRecords(); // Refresh the list
            $this->checkLastAttendance(); // Reset time-in and time-out flags
        }
    }

    public function deleteAttendance()
    {
        if ($this->isAdmin) {
            UserAttendance::truncate(); // Deletes all records from the attendance table
            $this->attendanceRecords = []; // Clear the local records
            $this->checkLastAttendance(); // Reset time-in and time-out flags
        }
    }

    public function updated($propertyName)
    {
        // Refresh attendance records when startDate or endDate is updated
        if (in_array($propertyName, ['startDate', 'endDate', 'search'])) {

            GlobalFilter::updateOrCreate(
                ['filter_name' => 'attendance_date_range'],
                [
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ]
            );
            $this->filterAttendanceRecords();
        }
    }

    public function render()
    {
        return view('livewire.attendance.attendance');
    }
}