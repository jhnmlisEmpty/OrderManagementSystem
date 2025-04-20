<?php

namespace App\Livewire\Payslip;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\OsrPayslipInfo;
use App\Models\UserAttendance;

class OsrPayslip extends Component
{
    use WithFileUploads;
    
    public $userId;
    public $startDate;
    public $endDate;
    public $attendanceRecords = [];
    public $salaryRate;
    public $totalSalary = 0; // Store the computed total salary
    public $dailySalaryRate = 0; // Assume this is provided
    public $workingHoursPerDay = 8;
    public $totalHours = 0; // Total hours worked
    public $totalMinutes = 0; // Total minutes worked
    public $totalSeconds = 0; // Total seconds worked
    public $presentDays = 0;
    
    public $qrCode;
    public $paymentProof;

    public $baseSalary;
    public $sss;
    public $philhealth;
    public $pagibig;
    public $otherDeduction;
    public $otherDeductionNotes;
    public $accountName;
    public $accountNumber;
    public $paymentMethod;
    public $qrcode;
    public $releaseDate;
    public $status = 'not released'; // Default status
    public $incentives;

    public $netPay;
    
    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
        $this->startDate = now()->subMonth()->format('Y-m-d'); // Default: one month ago
        $this->endDate = now()->format('Y-m-d'); // Default: today

        $this->dailySalaryRate = $this->salaryRate; // Example: ₱500/day

        $existingPayslip = OsrPayslipInfo::where('user_id', $this->userId)
            ->first();

        if ($existingPayslip) {
            $this->startDate = $existingPayslip->start_date;
            $this->endDate = $existingPayslip->end_date;
            $this->dailySalaryRate = $existingPayslip->base_salary;
            $this->sss = $existingPayslip->sss;
            $this->philhealth = $existingPayslip->philhealth;
            $this->pagibig = $existingPayslip->pagibig;
            $this->otherDeduction = $existingPayslip->other_deductions;
            $this->otherDeductionNotes = $existingPayslip->note_for_deduction;
            $this->accountName = $existingPayslip->account_name;
            $this->accountNumber = $existingPayslip->account_number;
            $this->paymentMethod = $existingPayslip->payment_method;
            $this->qrCode = $existingPayslip->qrcode;
            $this->releaseDate = $existingPayslip->release_date;
            $this->paymentProof = $existingPayslip->payment_proof;
            $this->status = $existingPayslip->status;
            $this->incentives = $existingPayslip->incentives;
        }

        $this->filterAttendanceRecords();
        $this->computeTotalSalary();
        $this->computeTotalTimeWorked();
        $this->computePresentDays();
    }


    public function savePayslip()
    {
        // Validate the file uploads
        $this->validate([
            'qrCode' => 'nullable|max:1024', // Max 1MB
            'paymentProof' => 'nullable|max:1024', // Max 1MB
        ]);



        // Handle file uploads
        if ($this->qrCode instanceof \Illuminate\Http\UploadedFile) {
            $qrCodePath = $this->qrCode->store('qrcodes', 'public');
        } else {
            $qrCodePath = $this->qrCode;
        }

        if ($this->paymentProof instanceof \Illuminate\Http\UploadedFile) {
            $paymentProofPath = $this->paymentProof->store('payment_proofs', 'public');
        } else {
            $paymentProofPath = $this->paymentProof;
        }

        // Update the payslip record
        $payslip = OsrPayslipInfo::where('user_id', $this->userId)
            ->first();

        if ($payslip) {
            $payslip->update([
            'base_salary' => $this->dailySalaryRate,
            'sss' => $this->sss,
            'philhealth' => $this->philhealth,
            'pagibig' => $this->pagibig,
            'other_deductions' => $this->otherDeduction,
            'note_for_deduction' => $this->otherDeductionNotes,
            'account_name' => $this->accountName,
            'account_number' => $this->accountNumber,
            'payment_method' => $this->paymentMethod,
            'qrcode' => $qrCodePath,
            'release_date' => $this->releaseDate,
            'payment_proof' => $paymentProofPath,
            'status' => $this->status,
            'incentives' => $this->incentives,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            ]);
        } else {
            OsrPayslipInfo::create([
            'user_id' => $this->userId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'base_salary' => $this->dailySalaryRate,
            'sss' => $this->sss,
            'philhealth' => $this->philhealth,
            'pagibig' => $this->pagibig,
            'other_deductions' => $this->otherDeduction,
            'note_for_deduction' => $this->otherDeductionNotes,
            'account_name' => $this->accountName,
            'account_number' => $this->accountNumber,
            'payment_method' => $this->paymentMethod,
            'qrcode' => $qrCodePath,
            'release_date' => $this->releaseDate,
            'payment_proof' => $paymentProofPath,
            'status' => $this->status,
            'incentives' => $this->incentives,
            ]);
        }

        // Emit a success message
        session()->flash('message', 'Payslip saved successfully!');
    }

    public function computeNetPay()
    {
        // Ensure all deduction and incentive values are numeric
        $this->sss = (float) $this->sss;
        $this->philhealth = (float) $this->philhealth;
        $this->pagibig = (float) $this->pagibig;
        $this->otherDeduction = (float) $this->otherDeduction;
        $this->incentives = (float) $this->incentives;

        // Calculate the net payout
        $this->netPay = $this->totalSalary 
                        - $this->sss 
                        - $this->philhealth 
                        - $this->pagibig 
                        - $this->otherDeduction 
                        + $this->incentives;
    }

    public function markAsReleased()
    {
        $this->status = 'released';
        $this->savePayslip();
    }

    public function markAsNotReleased()
    {
        $this->status = 'pending';
        $this->releaseDate = null; // Clear the release date
        $this->savePayslip();
    }

    public function computePresentDays()
    {
        // Count the number of unique dates with valid time_in or time_out
        $this->presentDays = $this->attendanceRecords->groupBy('date')->filter(function ($records) {
            return $records->contains(function ($record) {
            return $record->time_in !== null || $record->time_out !== null;
            });
        })->count();
    }

    public function computeTotalTimeWorked()
    {
        // Sum up all seconds worked
        $totalSecondsWorked = $this->attendanceRecords->sum('seconds_elapsed');

        // Convert total seconds into hours, minutes, and seconds
        $this->totalHours = floor($totalSecondsWorked / 3600);
        $this->totalMinutes = floor(($totalSecondsWorked % 3600) / 60);
        $this->totalSeconds = $totalSecondsWorked % 60;
    }

    public function filterAttendanceRecords()
    {
        $this->attendanceRecords = UserAttendance::where('user_id', $this->userId)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->get();
    }

    public function computeTotalSalary()
    {
        $this->dailySalaryRate = (float) $this->dailySalaryRate;

        // Convert daily salary rate to hourly and per-minute rates
        $hourlyRate = $this->dailySalaryRate / $this->workingHoursPerDay; // Example: ₱500 / 8 = ₱62.5/hour
        $minuteRate = $hourlyRate / 60; // Example: ₱62.5 / 60 = ₱1.04/minute

        // Calculate total salary based on attendance records
        $this->totalSalary = $this->attendanceRecords->sum(function ($record) use ($minuteRate) {
            $minutesWorked = $record->seconds_elapsed / 60; // Convert seconds to minutes
            return $minutesWorked * $minuteRate; // Calculate salary for each record
        });

        $this->computeNetPay();
    }

    public function updated($propertyName)
    {
        // Refresh attendance records when startDate or endDate is updated
        if (in_array($propertyName, ['dailySalaryRate','startDate', 'endDate','sss', 'philhealth', 'pagibig', 'otherDeduction','incentives','otherDeductionNotes','accountName','accountNumber','paymentMethod','releaseDate'])) {
            $this->filterAttendanceRecords();
            $this->computeTotalSalary();
            $this->computeTotalTimeWorked();
            $this->computePresentDays(); 
            $this->savePayslip();  
        }
    }




    public function render()
    {
        return view('livewire.payslip.osr-payslip', [
            'attendanceRecords' => (float) $this->attendanceRecords,
            'totalSalary' => (float) $this->totalSalary,
            'totalHours' => (float) $this->totalHours,
            'totalMinutes' => (float) $this->totalMinutes,
            'presentDays' => (float) $this->presentDays,
            'totalSeconds' => (float) $this->totalSeconds,
            'netPay' => (float) $this->netPay,
        ]);
    }
}
