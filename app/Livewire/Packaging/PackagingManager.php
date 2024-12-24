<?php

namespace App\Livewire\Packaging;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Packaging;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PackagingManager extends Component
{
    use WithPagination, LivewireAlert;

    public $name;
    public $quantity;
    public $packagingId;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
    ];

    protected $listeners = [
        'confirmSave',
        'cancelSave',
    ];

    public function mount()
    {
        $this->resetInputFields();
    }

    public function saveOrderWithItems()
    {
        $this->alert('question', 'Are you sure you want to save this packaging?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmSave',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelSave',
        ]);
    }

    public function confirmSave()
    {
        $this->validate();

        if ($this->packagingId) {
            $packaging = Packaging::findOrFail($this->packagingId);
            $packaging->update([
                'name' => $this->name,
                'quantity' => $this->quantity,
            ]);
            $this->alert('success', 'Packaging updated successfully.');
        } else {
            Packaging::create([
                'name' => $this->name,
                'quantity' => $this->quantity,
            ]);
            $this->alert('success', 'Packaging created successfully.');
        }

        $this->resetInputFields();
    }

    public function cancelSave()
    {
        $this->alert('info', 'Packaging save canceled.');
    }

    public function edit($id)
    {
        $packaging = Packaging::findOrFail($id);
        $this->packagingId = $packaging->id;
        $this->name = $packaging->name;
        $this->quantity = $packaging->quantity;
    }

    public function delete($id)
    {
        $packaging = Packaging::findOrFail($id);
        $packaging->delete();
        $this->alert('success', 'Packaging deleted successfully.');
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->quantity = '';
        $this->packagingId = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $packagings = Packaging::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.packaging.packaging-manager', [
            'packagings' => $packagings,
        ]);
    }

}