<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PackageSold implements FromView, WithEvents//, ShouldAutoSize//, WithDrawings//
{
    public function __construct($data){
        $totalPackage = array();
        $totalGender = array();
        $totalStatus = array();
        $totalType = array();

        $temp = [
            "Fit to work" => 0,
            "Physically fit with minor illness" => 0,
            "Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)" => 0,
            "Unfit to work" => 0,
            "" => 0
        ];

        $ageGroups = [
            'below 18' => 0,
            '18-29'    => 0,
            '30-40'    => 0,
            '41-50'    => 0,
            '51-60'    => 0,
            '61-70'    => 0,
            '70+'      => 0
        ];

        foreach($data as $row){
            isset($totalPackage[strtoupper($row->package->name)]) ? $totalPackage[strtoupper($row->package->name)]++ : $totalPackage[strtoupper($row->package->name)] = 1;
            isset($totalGender[strtoupper($row->user->gender)]) ? $totalGender[strtoupper($row->user->gender)]++ : $totalGender[strtoupper($row->user->gender)] = 1;
            isset($totalStatus[strtoupper($row->status)]) ? $totalStatus[strtoupper($row->status)]++ : $totalStatus[strtoupper($row->status)] = 1;
            isset($totalType[strtoupper($row->type)]) ? $totalType[strtoupper($row->type)]++ : $totalType[strtoupper($row->type)] = 1;

            $temp[$row->classification]++;

            $age = now()->parse($row->user->birthday)->age;

            if ($age < 18) $ageGroups['below 18']++;
            elseif ($age <= 29) $ageGroups['18-29']++;
            elseif ($age <= 40) $ageGroups['30-40']++;
            elseif ($age <= 50) $ageGroups['41-50']++;
            elseif ($age <= 60) $ageGroups['51-60']++;
            elseif ($age <= 70) $ageGroups['61-70']++;
            else $ageGroups['70+']++;
        }

        $totalClassification = [];
        $letters = range('A', 'D'); // enough letters
        $i = 0;

        foreach ($temp as $key => $value) {
            if ($key === "") {
                $totalClassification["Pending"] = $value;
            } else {
                $totalClassification[$letters[$i] . ": " . $key] = $value;
                $i++;
            }
        }

        arsort($totalPackage);
        arsort($totalGender);
        arsort($totalStatus);
        arsort($totalType);
        // arsort($totalClassification);

        $data->totalPackage = $totalPackage;
        $data->totalGender = $totalGender;
        $data->totalStatus = $totalStatus;
        $data->totalType = $totalType;
        $data->totalClassification = $totalClassification;
        $data->ageGroups = $ageGroups;

        $data->maxLength = max(array_map('count', [$totalPackage, $totalGender, $totalStatus, $totalType, $totalClassification, $ageGroups]));

        $this->data          = $data;
        $this->maxLength     = $data->maxLength;
    }

    public function view(): View
    {
        return view('exports.packagesSold', [
            'data' => $this->data,
        ]);
    }

    public function registerEvents(): array
    {
        $borderStyle = 
        [
            [//0
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ],
            [//1
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                ]
            ],
            [//2
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                ]
            ],
            [//3
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ],
            [//4
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                ]
            ],
            [//5
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                ]
            ],
            [//6
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFF']
                    ],
                ]
            ],
            [//7
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFF']
                    ],
                ]
            ],
            [//8
                'borders' => [
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFF']
                    ],
                ]
            ],
            [//9
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFFFFF']
                    ],
                ]
            ],
            [//10
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                ]
            ],
            [//11
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ]
            ],
            [//12
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ]
            ],
            [//13
                'borders' => [
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ]
            ],
            [//14
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ]
            ],
        ];

        $fillStyle = [
            [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'FFFF00'
                    ]
                ],
            ],
            [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ebf8a4'
                    ]
                ],
            ]
        ];

        $headingStyle = [
            [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                ]
            ],
            [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ]
            ],
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ]
            ],
            [
                'font' => [
                    'bold' => true
                ],
            ],
            [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            [
                'font' => [
                    'underline' => true
                ],
            ],
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                ],
            ],
        ];

        return [
            AfterSheet::class => function(AfterSheet $event) use ($borderStyle, $fillStyle, $headingStyle) {
                // SHEET SETTINGS
                $size = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize($size);
                $event->sheet->getDelegate()->setTitle("Packages Sold", false);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(0);
                $event->sheet->getDelegate()->getPageMargins()->setTop(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setLeft(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setBottom(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setRight(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setHeader(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setFooter(0.5);
                $event->sheet->getDelegate()->getPageSetup()->setHorizontalCentered(true);
                // $event->sheet->getDelegate()->getPageSetup()->setVerticalCentered(true);

                // SET PAGE BREAK PREVIEW
                $temp = new \PhpOffice\PhpSpreadsheet\Worksheet\SheetView;
                $event->sheet->getParent()->getActiveSheet()->setSheetView($temp->setView('pageBreakPreview'));
                
                // SET DEFAULT FONT
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setSize(10);

                // CELL COLOR
                // $event->sheet->getDelegate()->getStyle('E3:E7')->getFont()->getColor()->setRGB('0000FF');

                // TEXT ROTATION
                // $event->sheet->getDelegate()->getStyle('B11')->getAlignment()->setTextRotation(90);

                // FUNCTIONS
                // $osSize = sizeof($this->linedUps);
                // $ofsSize = sizeof($this->onBoards);

                // GET AFTER ONSIGNERS
                // $ar = function($c1, $r1, $c2 = null, $r2 = null, $ofs = false) use($osSize, $ofsSize){
                //     $size = $osSize;
                //     $temp = $c1 . ($r1 + $size);
                //     if($c2 != null){
                //         $temp .= ':' . $c2 . ($r2 + ($size + ($ofs ? $ofsSize : 0)));
                //     }

                //     return $temp;
                // };

                // FONT SIZES

                // HEADINGS

                // HC B
                $h[0] = [
                    
                ];

                // VT
                $h[1] = [
                    
                ];

                // HL B
                $h[2] = [
                    
                ];

                // HC
                $h[3] = [
                    
                ];

                // HC VC
                $h[4] = [
                    // 'A1:Y' . (sizeof($this->data) + 1)
                    'A1:AB' . (sizeof($this->data) + $this->maxLength + 3)
                ];

                // HL
                $h[5] = [
                ];

                // B
                $h[6] = [
                ];

                // VC
                $h[7] = [
                ];

                // UNDERLINE
                $h[8] = [
                ];

                // JUSTIFY
                $h[9] = [
                ];

                $h['wrap'] = [
                    'B' . (sizeof($this->data) + 3) . ':T' . (sizeof($this->data) + $this->maxLength + 3)
                ];

                // SHRINK TO FIT
                $h['stf'] = [
                    'D2:G' . (sizeof($this->data) + 1),
                    'K2:K' . (sizeof($this->data) + 1)
                ];

                foreach($h as $key => $value) {
                    foreach($value as $col){
                        if($key === 'wrap'){
                            $event->sheet->getDelegate()->getStyle($col)->getAlignment()->setWrapText(true);
                        }
                        elseif($key == 'stf'){
                            $event->sheet->getDelegate()->getStyle($col)->getAlignment()->setWrapText(false);
                            $event->sheet->getDelegate()->getStyle($col)->applyFromArray([
                                'alignment' => [
                                    'shrinkToFit' => true
                                ],
                            ]);
                        }
                        else{
                            $event->sheet->getDelegate()->getStyle($col)->applyFromArray($headingStyle[$key]);
                        }
                    }
                }

                // FILLS
                $fills[0] = [
                    'B' . (sizeof($this->data) + 3) . ':E' . (sizeof($this->data) + 3),
                    'G' . (sizeof($this->data) + 3) . ':K' . (sizeof($this->data) + 3),
                    'M' . (sizeof($this->data) + 3) . ':N' . (sizeof($this->data) + 3),
                    'P' . (sizeof($this->data) + 3) . ':Q' . (sizeof($this->data) + 3),
                    'S' . (sizeof($this->data) + 3) . ':T' . (sizeof($this->data) + 3),
                    'V' . (sizeof($this->data) + 3) . ':W' . (sizeof($this->data) + 3),
                ];

                $fills[1] = [
                    
                ];

                foreach($fills as $key => $value){
                    foreach($value as $cell){
                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($fillStyle[$key]);
                    }
                }

                // BORDERS

                // ALL BORDER THIN
                $cells[0] = array_merge([
                    'A1:AB' . (sizeof($this->data) + 1),
                    'B' . (sizeof($this->data) + 3) . ':E' . (sizeof($this->data) + sizeof($this->data->totalPackage) + 3),
                    'G' . (sizeof($this->data) + 3) . ':K' . (sizeof($this->data) + sizeof($this->data->totalClassification) + 3),
                    'M' . (sizeof($this->data) + 3) . ':N' . (sizeof($this->data) + sizeof($this->data->ageGroups) + 3),
                    'P' . (sizeof($this->data) + 3) . ':Q' . (sizeof($this->data) + sizeof($this->data->totalType) + 3),
                    'S' . (sizeof($this->data) + 3) . ':T' . (sizeof($this->data) + sizeof($this->data->totalStatus) + 3),
                    'V' . (sizeof($this->data) + 3) . ':W' . (sizeof($this->data) + sizeof($this->data->totalGender) + 3),
                ]);

                // ALL BORDER MEDIUM
                $cells[1] = array_merge([
                ]);

                // ALL BORDER THICK
                $cells[2] = array_merge([
                ]);

                // OUTSIDE BORDER THIN
                $cells[3] = array_merge([
                ]);

                // OUTSIDE BORDER MEDIUM
                $cells[4] = array_merge([
                ]);

                // OUTSIDE BORDER THICK
                $cells[5] = array_merge([
                ]);

                // TOP REMOVE BORDER
                $cells[6] = array_merge([
                ]);

                // BRB
                $cells[7] = array_merge([
                ]);

                // LRB
                $cells[8] = array_merge([

                ]);

                // RRB
                $cells[9] = array_merge([
                ]);

                // TRB
                $cells[10] = array_merge([
                ]);

                // TBT - TOP BORDER THIN
                $cells[11] = array_merge([
                ]);

                // BBT
                $cells[12] = array_merge([
                ]);

                // LBT
                $cells[13] = array_merge([
                ]);

                // RBT
                $cells[14] = array_merge([
                ]);
                
                foreach($cells as $key => $value){
                    foreach($value as $cell){
                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($borderStyle[$key]);
                    }
                }

                // FOR THE CHECK
                // $event->sheet->getDelegate()->getStyle('L46')->getFont()->setName('Marlett');

                // COLUMN RESIZE
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(6);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(17);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(16);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(16);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(7);

                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);

                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(6);

                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(14);

                // ROW RESIZE
                $rows = [
                    // [
                    //     -1, //ROW HEIGHT
                    //     2,3 //START ROW, END ROW
                    // ],
                ];

                $rows2 = [
                    // [
                    //     40,
                    //     [11,14,17,20]
                    // ]
                ];

                foreach($rows as $row){
                    for($i = $row[1]; $i <= $row[2]; $i++){
                        $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight($row[0]);
                    }
                }

                foreach($rows2 as $row){
                    foreach($row[1] as $cell){
                        $event->sheet->getDelegate()->getRowDimension($cell)->setRowHeight($row[0]);
                    }
                }

                for($i = (sizeof($this->data) + 4); $i < (sizeof($this->data) + $this->maxLength + 3); $i++){
                    if(strlen($event->sheet->getCell("B$i")->getValue()) >= 35 || strlen($event->sheet->getCell("G$i")->getValue()) >= 43){
                        $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(35);
                    }
                }

                // PAGE BREAKS
                $rows = [];
                foreach($rows as $row){
                    $event->sheet->getParent()->getActiveSheet()->setBreak('A' . $row, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                // SET PRINT AREA
                // $event->sheet->getDelegate()->getPageSetup()->setPrintArea("C1:Y42");

                // CUSTOM FONT AND STYLE TO DEFINED CELL
                // $event->sheet->getDelegate()->getStyle('A1:L150')->getFont()->setSize(14);
                // $event->sheet->getDelegate()->getStyle('A1:L150')->getFont()->setName('Arial');
            },
        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Letter Head');
        $drawing->setDescription('Letter Head');
        $drawing->setPath(public_path("images/letter_head.jpg"));
        $drawing->setResizeProportional(false);
        $drawing->setHeight(115);
        $drawing->setWidth(2200);
        $drawing->setOffsetX(4);
        $drawing->setOffsetY(4);
        $drawing->setCoordinates('C1');

        $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing2->setName('Avatar');
        $drawing2->setDescription('Avatar');
        $drawing2->setPath(public_path($this->data->user->avatar));
        $drawing2->setResizeProportional(false);
        $drawing2->setHeight(230);
        $drawing2->setWidth(230);
        $drawing2->setOffsetX(5);
        $drawing2->setOffsetY(2);
        $drawing2->setCoordinates('C3');

        return [$drawing, $drawing2];
    }
}