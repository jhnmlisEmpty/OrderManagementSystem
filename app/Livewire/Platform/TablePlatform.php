<?php

namespace App\Livewire\Platform;

use Livewire\Component;
use App\Models\PlatformOrder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class TablePlatform extends Component
{
    use LivewireAlert;
    public $startDate;
    public $endDate;

    protected $listeners = [
        'confirmDelete',
        'cancelDelete'
    ];

    public function mount()
    {
        $this->startDate = Carbon::now()->subMonth()->format('Y-m-d'); // Default start date to one month ago
        $this->endDate = Carbon::now()->format('Y-m-d'); // Default end date to today
        
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate'])) {
            $this->render();
        }
    }

    public function deletePlatformOrder($id)
    {
        $this->alert('question', 'Are you sure you want to delete this order?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmDelete',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelDelete',
            'data' => [
                    'id' => $id
                ]
        ]);
    }

    public function confirmDelete($data)
    { 
        $id = $data['id'];

        $order = PlatformOrder::findOrFail($id);
        $order->delete();

        $this->alert('success', 'Order deleted successfully!');
    }

    public function cancelDelete()
    {
        $this->alert('info', 'Order deletion canceled.');
    }

    public function render()
    {
        // $salesData = PlatformOrder::select('platform', 'page', DB::raw('SUM(full_amount) as total_sales'))
        //     ->where('created_at', '>=', \Carbon\Carbon::now()->subMonth())
        //     ->groupBy('platform', 'page')
        //     ->get()
        //     ->groupBy('platform');


        // $platformOrders = PlatformOrder::where('platform', 'like', '%' . $this->search . '%')
        //     ->orWhereHas('platformOrderItems.product', function ($query) {
        //         $query->where('name', 'like', '%' . $this->search . '%');
        //     })
        //     ->orWhere('page', 'like', '%' . $this->search . '%')
        //     ->orWhere('platform', 'like', '%' . $this->search . '%')
        //     ->get();

        $salesData = PlatformOrder::select('platform', 'page', DB::raw('SUM(full_amount) as total_sales'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('platform', 'page')
            ->get()
            ->groupBy('platform');

        $platformOrders = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        return view('livewire.platform.table-platform', compact('platformOrders','salesData'));
    }
}
