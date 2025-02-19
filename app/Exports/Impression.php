<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Model\ExamList;

class Impression implements FromView, WithEvents, WithDrawings//, ShouldAutoSize
{
    public function __construct($data, $settings){
        $data->doctor_id = 11;

        $this->data     = $data;
        $this->settings     = $settings;

    }

    public function view(): View
    {
        return view('exports.impressions', [
            'data' => $this->data,
            'settings' => $this->settings,
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
                        'rgb' => 'bdb9b9'
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
                $event->sheet->getDelegate()->setTitle('RESULT', false);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(0);
                $event->sheet->getDelegate()->getPageMargins()->setTop(0.3);
                $event->sheet->getDelegate()->getPageMargins()->setLeft(0.2);
                $event->sheet->getDelegate()->getPageMargins()->setBottom(0.3);
                $event->sheet->getDelegate()->getPageMargins()->setRight(0.2);
                $event->sheet->getDelegate()->getPageMargins()->setHeader(0.5);
                $event->sheet->getDelegate()->getPageMargins()->setFooter(0.5);
                $event->sheet->getDelegate()->getPageSetup()->setHorizontalCentered(true);
                // $event->sheet->getDelegate()->getPageSetup()->setVerticalCentered(true);

                // SET PAGE BREAK PREVIEW
                $temp = new \PhpOffice\PhpSpreadsheet\Worksheet\SheetView;
                $event->sheet->getParent()->getActiveSheet()->setSheetView($temp->setView('pageBreakPreview'));
                
                // SET DEFAULT FONT
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setSize(8);

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
                    'B6', 'D6:D10', 'F6:F10'
                ];

                // HC VC
                $h[4] = [
                    'A2:A5', 'C49:G49'
                ];

                // HL
                $h[5] = [
                ];

                // B
                $h[6] = [
                    'A2:A5', 'A6', 'C6:C7', 'E6', 'A8', 'C8', 'E8', 'G8', 'C10', 'E10', 'A11', 'C11', 'A13', 'C13', 'G13', 'G15', 'A17:A22', 'C18', 'C20:C21', 'F18',
                    'A23:G24', 'C49:G50', 'C25:C26', 'A29:G29', 'A32:A39', 'C39', 'A45:G45', 'A48',
                    'C49:G50'
                ];

                // VC
                $h[7] = [
                    'A2:G50'
                ];

                // UNDERLINE
                $h[8] = [
                    'A24'
                ];

                // JUSTIFY
                $h[9] = [
                ];

                $h['wrap'] = [
                    'A40', 'C40', 'D49'
                ];

                // SHRINK TO FIT
                $h['stf'] = [
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
                    'C6:G10', 'A39:G44', 'A48:G48', 'C49:G50'
                ]);

                // ALL BORDER MEDIUM
                $cells[1] = array_merge([
                ]);

                // ALL BORDER THICK
                $cells[2] = array_merge([
                ]);

                // OUTSIDE BORDER THIN
                $cells[3] = array_merge([
                    'A6:B7', 'A8:B10', 'A11:B12', 'C11:F12', 'G11:G12', 'A13:B16', 'C13:F16', 'G13:G16',
                    'A18:G22', 'A24:G28', 'A30:G31', 'A33:G38', 'A46:G47', 'A49:B50', 'A46:B47'
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
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(14);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(47);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(14);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(16);

                // ROW RESIZE
                $rows = [
                    [
                        15, //ROW HEIGHT
                        2,50 //START ROW, END ROW
                    ],
                ];

                $rows2 = [
                    [
                        60,
                        [1]
                    ],
                    [30,[5]]
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
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setSize(14);
            },
        ];
    }

    public function drawings()
    {
        $array = [];

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(public_path($this->settings['logo']));
        $drawing->setResizeProportional(false);
        $drawing->setHeight(65);
        $drawing->setWidth(400);
        $drawing->setOffsetX(70);
        $drawing->setOffsetY(10);
        $drawing->setCoordinates('B1');
        array_push($array, $drawing);

        if($this->data->doctor->doctor->signature){
            $temp = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $temp->setPath(public_path($this->data->doctor->doctor->signature));
            $temp->setResizeProportional(false);
            $temp->setHeight(35);
            $temp->setWidth(70);
            $temp->setOffsetX(20);
            $temp->setOffsetY(1);
            $temp->setCoordinates('A46');
            array_push($array, $temp);

            $temp2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $temp2->setPath(public_path($this->data->doctor->doctor->signature));
            $temp2->setResizeProportional(false);
            $temp2->setHeight(35);
            $temp2->setWidth(70);
            $temp2->setOffsetX(20);
            $temp2->setOffsetY(1);
            $temp2->setCoordinates('A49');
            array_push($array, $temp2);
        }

        return $array;
    }
}
