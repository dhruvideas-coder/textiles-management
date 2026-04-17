<?php

namespace App\Livewire;

use Livewire\Component;

class ChallanGrid extends Component
{
    public array $grid = [];
    public $total_meters = 0;
    public $total_pieces = 0;
    public $column_totals = [];
    
    public function mount($initialData = [])
    {
        // Initialize 12 rows, 6 columns (1-indexed)
        $this->column_totals = array_fill(1, 6, 0);
        
        for ($r = 1; $r <= 12; $r++) {
            for ($c = 1; $c <= 6; $c++) {
                $this->grid[$r][$c] = $initialData[$r][$c] ?? '';
            }
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->total_meters = 0;
        $this->total_pieces = 0;
        $this->column_totals = array_fill(1, 6, 0);

        for ($r = 1; $r <= 12; $r++) {
            for ($c = 1; $c <= 6; $c++) {
                $val = floatval($this->grid[$r][$c] ?? 0);
                if ($val > 0) {
                    $this->column_totals[$c] += $val;
                    $this->total_pieces++;
                    $this->total_meters += $val;
                }
            }
        }

        // Notify parent form (CreateChallan/EditChallan)
        $this->dispatch('grid-updated', 
            data: $this->grid, 
            total_meters: $this->total_meters, 
            total_pieces: $this->total_pieces
        );
    }

    public function render()
    {
        return view('livewire.challan-grid');
    }
}
