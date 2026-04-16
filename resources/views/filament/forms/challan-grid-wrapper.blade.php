<div>
    @php
        $record = $getRecord();
        $initialData = [];
        if ($record && $record->items) {
            foreach ($record->items as $item) {
                $initialData[$item->row_no][$item->column_no] = $item->meters;
            }
        }
    @endphp
    <livewire:challan-grid :initialData="$initialData" />
</div>
