// app/Livewire/Admin/VoucherManagement.php
<?php

namespace App\Livewire\Admin;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class VoucherManagement extends Component
{
    use WithPagination;

    #[Rule('required|string|unique:vouchers,voucher_code')]
    public $voucher_code = '';

    #[Rule('required|string|max:255')]
    public $voucher_name = '';

    #[Rule('nullable|string')]
    public $voucher_description = '';

    #[Rule('required|in:point_redemption,free_claim')]
    public $voucher_type = 'free_claim';

    #[Rule('nullable|integer|min:1')]
    public $required_points = null;

    #[Rule('required|numeric|min:0')]
    public $discount_amount = 0;

    #[Rule('required|in:fixed,percentage')]
    public $discount_type = 'fixed';

    #[Rule('required|date')]
    public $valid_from = '';

    #[Rule('required|date|after:valid_from')]
    public $valid_until = '';

    #[Rule('nullable|integer|min:1')]
    public $max_usage = null;

    public $search = '';
    public $filterType = '';
    public $showModal = false;
    public $editingVoucherId = null;

    protected $queryString = ['search', 'filterType'];

    public function mount()
    {
        $this->valid_from = now()->format('Y-m-d');
        $this->valid_until = now()->addMonth()->format('Y-m-d');
    }

    public function render()
    {
        $vouchers = Voucher::query()
            ->when($this->search, fn($query) => 
                $query->where('voucher_name', 'like', '%' . $this->search . '%')
                      ->orWhere('voucher_code', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterType, fn($query) => $query->where('voucher_type', $this->filterType))
            ->with('creator')
            ->withCount('voucherClaims')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.voucher-management', compact('vouchers'));
    }

    public function createVoucher()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editVoucher($voucherId)
    {
        $voucher = Voucher::findOrFail($voucherId);
        
        $this->editingVoucherId = $voucherId;
        $this->voucher_code = $voucher->voucher_code;
        $this->voucher_name = $voucher->voucher_name;
        $this->voucher_description = $voucher->voucher_description;
        $this->voucher_type = $voucher->voucher_type;
        $this->required_points = $voucher->required_points;
        $this->discount_amount = $voucher->discount_amount;
        $this->discount_type = $voucher->discount_type;
        $this->valid_from = $voucher->valid_from->format('Y-m-d');
        $this->valid_until = $voucher->valid_until->format('Y-m-d');
        $this->max_usage = $voucher->max_usage;
        
        $this->showModal = true;
    }

    public function saveVoucher()
    {
        // Update validation rule for editing
        if ($this->editingVoucherId) {
            $this->voucher_code = trim($this->voucher_code);
            $existingVoucher = Voucher::where('voucher_code', $this->voucher_code)
                ->where('voucher_id', '!=', $this->editingVoucherId)
                ->exists();
            
            if ($existingVoucher) {
                $this->addError('voucher_code', 'Kode voucher sudah digunakan.');
                return;
            }
        }

        $this->validate();

        // Additional validation for point redemption
        if ($this->voucher_type === 'point_redemption' && !$this->required_points) {
            $this->addError('required_points', 'Points diperlukan untuk voucher point redemption.');
            return;
        }

        $voucherData = [
            'voucher_code' => strtoupper($this->voucher_code),
            'voucher_name' => $this->voucher_name,
            'voucher_description' => $this->voucher_description,
            'voucher_type' => $this->voucher_type,
            'required_points' => $this->voucher_type === 'point_redemption' ? $this->required_points : null,
            'discount_amount' => $this->discount_amount,
            'discount_type' => $this->discount_type,
            'valid_from' => $this->valid_from,
            'valid_until' => $this->valid_until,
            'max_usage' => $this->max_usage,
            'created_by' => auth()->id(),
        ];

        if ($this->editingVoucherId) {
            $voucher = Voucher::findOrFail($this->editingVoucherId);
            $voucher->update($voucherData);
            session()->flash('message', 'Voucher berhasil diperbarui!');
        } else {
            Voucher::create($voucherData);
            session()->flash('message', 'Voucher berhasil dibuat!');
        }

        $this->closeModal();
    }

    public function toggleVoucherStatus($voucherId)
    {
        $voucher = Voucher::findOrFail($voucherId);
        $voucher->update(['is_active' => !$voucher->is_active]);
        
        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Voucher berhasil {$status}!");
    }

    public function deleteVoucher($voucherId)
    {
        $voucher = Voucher::findOrFail($voucherId);
        
        // Check if voucher has been claimed
        if ($voucher->voucherClaims()->exists()) {
            session()->flash('error', 'Tidak dapat menghapus voucher yang sudah diklaim!');
            return;
        }
        
        $voucher->delete();
        session()->flash('message', 'Voucher berhasil dihapus!');
    }

    public function generateVoucherCode()
    {
        $this->voucher_code = 'LM' . strtoupper(uniqid());
    }

    public function updatedVoucherType()
    {
        if ($this->voucher_type === 'free_claim') {
            $this->required_points = null;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingVoucherId = null;
        $this->voucher_code = '';
        $this->voucher_name = '';
        $this->voucher_description = '';
        $this->voucher_type = 'free_claim';
        $this->required_points = null;
        $this->discount_amount = 0;
        $this->discount_type = 'fixed';
        $this->valid_from = now()->format('Y-m-d');
        $this->valid_until = now()->addMonth()->format('Y-m-d');
        $this->max_usage = null;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }
}