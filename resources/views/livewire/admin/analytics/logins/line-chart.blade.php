<div>
    <div class="d-flex justify-content-end align-items-center mb-4">
        <span class="p-1 me-2">Filter:</span>
        <select wire:model="filter"  wire:change="updateChart" class="form-select p-1 w-50">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option selected="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    
    <div style="height: 350px;">
        <livewire:livewire-line-chart key="{{ $lineChartModel->reactiveKey() }}" :line-chart-model="$lineChartModel" />
    </div>
</div>