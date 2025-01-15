<?php

namespace App\Livewire\Platform;

use Livewire\Component;
use App\Models\PlatformOrder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TablePlatform extends Component
{
    use LivewireAlert;
    
    public $search = '';

    protected $listeners = [
        'confirmDelete',
        'cancelDelete'
    ];

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
        $salesData = PlatformOrder::select('platform', 'page', DB::raw('SUM(full_amount) as total_sales'))
            ->where('created_at', '>=', \Carbon\Carbon::now()->subMonth())
            ->groupBy('platform', 'page')
            ->get()
            ->groupBy('platform');


        $platformOrders = PlatformOrder::where('platform', 'like', '%' . $this->search . '%')
            ->orWhereHas('platformOrderItems.product', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhere('page', 'like', '%' . $this->search . '%')
            ->orWhere('platform', 'like', '%' . $this->search . '%')
            ->get();

        return view('livewire.platform.table-platform', compact('platformOrders','salesData'));
    }
}
