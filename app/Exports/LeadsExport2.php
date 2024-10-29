<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\LeadsSheetExport2;

class LeadsExport2 implements FromCollection, WithMultipleSheets
{
    use Exportable;

    protected $leads;
    protected $titles;
    protected $headings;

    public function __construct($leads, $titles, $headings)
    {
        $this->leads = $leads;
        $this->titles = $titles;
        $this->headings = $headings;
    }

    public function collection()
    {
        return collect($this->leads);
    }

    public function sheets(): array
    {
        $sheets = [];
        $leads = $this->collection();
        foreach ($leads as $form_id => $lead) {
            $title = $this->titles[$form_id];
            $headings = $this->headings[$form_id];
            $sheets[] = new LeadsSheetExport2($lead, $title, $headings);
        }

        return $sheets;
    }
}
