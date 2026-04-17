<?php

namespace App\Livewire;

use Livewire\Component;

class ChallanGrid extends Component
{
    public array $grid = [];
    public $total_meters = 0;
    public $total_pieces = 0;
    public $column_totals = [0, 0, 0, 0, 0, 0];
    
    // In Filament, we'll emit the data back to the main form
    // Or we rely on this component if it's the main creation form?
    // Wait, let's keep it simple.
    
    public function mount($initialData = [])
    {
        // initialize 12 rows, 6 columns
        for ($r = 1; $r <= 12; $r++) {
            for ($c = 1; $c <= 6; $c++) {
                $this->grid[$r][$c] = $initialData[$r][$c] ?? '';
            }
        }
        $this->calculateTotals();
    }

    // We manualy call this from Alpine to sync data
    public function calculateTotals()
    {
        $this->total_meters = 0;
        $this->total_pieces = 0;
        $this->column_totals = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0];

        for ($c = 1; $c <= 6; $c++) {
            $colTotal = 0;
            for ($r = 1; $r <= 12; $r++) {
                $val = floatval($this->grid[$r][$c] ?? 0);
                if ($val > 0) {
                    $colTotal += $val;
                    $this->total_pieces++;
                }
            }
            $this->column_totals[$c] = floatval($colTotal);
            $this->total_meters += floatval($colTotal);
        }
        
        $this->dispatch('grid-updated', data: $this->grid, total_meters: $this->total_meters, total_pieces: $this->total_pieces);
    }

    public function render()
    {
        return view('livewire.challan-grid');
    }
}
