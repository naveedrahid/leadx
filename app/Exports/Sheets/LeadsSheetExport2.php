<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LeadsSheetExport2 implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $lead;
    protected $title;
    protected $headings;

    public function __construct($lead, $title, $headings)
    {
        $this->lead = $lead;
        $this->title = $title;
        $this->headings = $headings;
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
        return $this->headings;
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];
        foreach (range('A', 'Z') as $letter) {
            $styles[$letter] = [
                'font' => [
                    'name' => 'Arial',
                    'size' => 10
                ],
                'color' => [
                    'rgb' => '808080'
                ]
            ];

            $styles[1] = [
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'color' => [
                    'rgb' => '000000'
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_YELLOW],
                ]
            ];

            $key = $letter . '1';
            if(in_array($letter, range('A', 'D'))) {
                $styles[$key] = [
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'color' => [
                        'rgb' => '000000'
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_GREEN],
                    ]
                ];
            } elseif(in_array($letter, range('E', 'M'))) {
                $styles[$key] = [
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'color' => [
                        'rgb' => '000000'
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_CYAN],
                    ]
                ];
            }
        }

        return $styles;
    }
}