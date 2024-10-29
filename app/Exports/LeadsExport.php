<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\LeadsSheetExport;

class LeadsExport implements FromCollection, WithMultipleSheets
{
    use Exportable;

    protected $leads;

    public function __construct($leads)
    {
        $this->leads = $leads;
    }

    public function collection()
    {
        return collect($this->leads);
    }

    public function sheets(): array
    {
        $sheets = [];
        $leads = $this->collection();
        foreach ($leads as $lead) {
            $title = 'Lead ID ' . $lead[1]['id']['value'];
            $sheets[] = new LeadsSheetExport($lead, $title);
        }

        return $sheets;
    }
}
