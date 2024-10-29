<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsSheetExport implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $lead;
    protected $title;

    public function __construct($lead, $title)
    {
        $this->lead = $lead;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->lead;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return [
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 60,            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];
        foreach (['A', 'B'] as $letter) {
            $styles[$letter] = [
                'font' => [
                    'name' => 'Arial',
                    'size' => 10
                ],
                'color' => [
                    'rgb' => '808080'
                ]
            ];

            $styles[$letter.'1'] = [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'color' => [
                    'rgb' => '000000'
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_GREEN],
                ]
            ];

            $styles[$letter.'6'] = [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'color' => [
                    'rgb' => '000000'
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_CYAN],
                ]
            ];

            $styles[$letter.'16'] = [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'color' => [
                    'rgb' => '000000'
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_YELLOW],
                ]
            ];
        }

        return $styles;
    }
}