<?php

namespace App\Livewire\Admin\Analytics\Logins;

use Livewire\Component;
use App\Models\LoginLog;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Support\Facades\DB;

class LineChart extends Component
{

    public $filter = 'monthly';

    public function render()
    {
        $loginData = $this->getLoginData();

        $lineChartModel = (new LineChartModel())
            ->setTitle('Number of Logins')
            ->setDataLabelsEnabled(true)
            ->setXAxisVisible(true)
            ->setGridVisible(true);

        foreach ($loginData as $data) {
            $lineChartModel->addPoint($data->label, $data->value);
        }

        return view('livewire.admin.analytics.logins.line-chart')->with([
            'lineChartModel' => $lineChartModel,
        ]);
    }

    public function getLoginData()
    {
        $query = DB::table('login_logs')
            ->select(DB::raw($this->getSelectColumn() . ' as label'), DB::raw('count(*) as value'))
            ->groupBy('label')
            ->orderBy('label');

        return $query->get();
    }

    private function getSelectColumn()
    {
        switch ($this->filter) {
            case 'daily':
                return 'DAY(created_at)';
            case 'weekly':
                return 'DAYNAME(created_at)';
            case 'monthly':
                return 'MONTHNAME(created_at)';
            case 'yearly':
                return 'YEAR(created_at)';
            default:
                return 'MONTHNAME(created_at)';
        }
    }

    public function updateChart()
    {
        // This method can be empty or contain logic to update the chart
        // based on the current value of $this->filter
        $this->render();
    }
}
