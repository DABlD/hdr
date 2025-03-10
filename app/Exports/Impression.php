<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Model\ExamList;

class Impression implements FromView, WithEvents//, WithDrawings//, ShouldAutoSize
{
    public function __construct($data, $settings){
        $data->doctor_id = 11;

        $questions = array_combine(array_column($data->questions[179], 'id'), $data->questions[179]);
        $ids = array_column($questions, 'id');
        $ids = array_chunk($ids, ceil(count($ids) / 2));

        $this->data     = $data;
        $this->settings     = $settings;
        $this->SEsize   = sizeof($ids[0]);
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
                        'color' => ['argb' => '000000'],
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
                        'color' => ['argb' => '000000'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
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
                        'color' => ['argb' => 'D9FFFFFF']
                    ],
                ]
            ],
            [//7
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF']
                    ],
                ]
            ],
            [//8
                'borders' => [
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF']
                    ],
                ]
            ],
            [//9
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF']
                    ],
                ]
            ],
            [//10
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF'],
                    ],
                ]
            ],
            [//11
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF'],
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF'],
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D9FFFFFF'],
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
                $event->sheet->getDelegate()->getPageMargins()->setTop(0.1);
                $event->sheet->getDelegate()->getPageMargins()->setLeft(0.1);
                $event->sheet->getDelegate()->getPageMargins()->setBottom(0.1);
                $event->sheet->getDelegate()->getPageMargins()->setRight(0.1);
                $event->sheet->getDelegate()->getPageMargins()->setHeader(0.1);
                $event->sheet->getDelegate()->getPageMargins()->setFooter(0.1);
                $event->sheet->getDelegate()->getPageSetup()->setHorizontalCentered(true);
                // $event->sheet->getDelegate()->getPageSetup()->setVerticalCentered(true);

                // SET PAGE BREAK PREVIEW
                $temp = new \PhpOffice\PhpSpreadsheet\Worksheet\SheetView;
                $event->sheet->getParent()->getActiveSheet()->setSheetView($temp->setView('pageBreakPreview'));
                
                // SET DEFAULT FONT
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setSize(9);

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
                    "A10:H11",
                    "A13:H13",
                    'A' . (18 + $this->SEsize),
                    'D' . (18 + $this->SEsize),
                ];

                // HL B
                $h[2] = [
                    
                ];

                // HC
                $h[3] = [
                    'A1'
                ];

                // HC VC
                $h[4] = [
                    'A2:A5'
                ];

                // HL
                $h[5] = [
                ];

                // B
                $h[6] = [
                    'A2:A5'
                ];

                // VC
                $h[7] = [
                    'A2:G5',
                    'A6:H8',
                    'A14:H' . (14 + $this->SEsize),
                    'A' . (15+$this->SEsize) . ':H' . (15+$this->SEsize),
                    'A' . (20+$this->SEsize) . ':H' . (23+$this->SEsize),
                ];

                // UNDERLINE
                $h[8] = [
                ];

                // JUSTIFY
                $h[9] = [
                ];

                $h['wrap'] = [
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
                    'A6:H11', 'A13:H' . (14 + $this->SEsize),
                    'A' . (15+$this->SEsize) . ':H' . (15+$this->SEsize),
                ]);

                // ALL BORDER MEDIUM
                $cells[1] = array_merge([
                ]);

                // ALL BORDER THICK
                $cells[2] = array_merge([
                ]);

                // OUTSIDE BORDER THIN
                $cells[3] = array_merge([
                    'A' . (17+$this->SEsize) . ':C' . (18+$this->SEsize),
                    'D' . (17+$this->SEsize) . ':H' . (18+$this->SEsize),
                    'A' . (20+$this->SEsize) . ':C' . (21+$this->SEsize),
                    'D' . (20+$this->SEsize) . ':H' . (21+$this->SEsize),
                    'A' . (22+$this->SEsize) . ':H' . (23+$this->SEsize),
                ]);

                // OUTSIDE BORDER MEDIUM
                $cells[4] = array_merge([
                ]);

                // OUTSIDE BORDER THICK
                $cells[5] = array_merge([
                ]);

                // TOP REMOVE BORDER
                $cells[6] = array_merge([
                    'A5:H5',
                ]);

                // BRB
                $cells[7] = array_merge([
                    'A' . (17+$this->SEsize) . ':C' . (17+$this->SEsize), 'D' . (17+$this->SEsize) . ':H' . (17+$this->SEsize),
                    'A' . (20+$this->SEsize) . ':C' . (20+$this->SEsize), 'D' . (20+$this->SEsize) . ':H' . (20+$this->SEsize),
                    'A' . (22+$this->SEsize) . ':C' . (22+$this->SEsize), 'D' . (22+$this->SEsize) . ':H' . (22+$this->SEsize),
                ]);

                // LRB
                $cells[8] = array_merge([
                    'H5', 'H9', 'H12', 'H' . (14+$this->SEsize),
                    'H' . (16+$this->SEsize),
                    'H' . (19+$this->SEsize),
                    'H' . (22+$this->SEsize),
                    'H' . (23+$this->SEsize),
                ]);

                // RRB
                $cells[9] = array_merge([
                    'H5', 'H9', 'H12', 'H' . (14+$this->SEsize),
                    'H' . (16+$this->SEsize),
                    'H' . (19+$this->SEsize),
                ]);

                // REMOVE ALL BORDER
                $cells[10] = array_merge([
                    'A1:H4'
                ]);

                // REMOVE OUTSIDE BORDER
                $cells[11] = array_merge([

                ]);
                
                foreach($cells as $key => $value){
                    foreach($value as $cell){
                        // dd([
                        //         'borders' => [
                        //             'allBorders' => [
                        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        //                 'color' => ['argb' => 'FFFFFF'],
                        //             ],
                        //         ],
                        //     ],
                        //     $borderStyle[$key]
                        // );

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($borderStyle[$key]);
                    }
                }

                // FOR THE CHECK
                // $event->sheet->getDelegate()->getStyle('L46')->getFont()->setName('Marlett');

                // COLUMN RESIZE
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(19);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(17);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(23);

                // ROW RESIZE
                $rows = [
                    [
                        11, //ROW HEIGHT
                        2,5 //START ROW, END ROW
                    ],
                    [15,6,9],
                    [15,14,14 + $this->SEsize],
                    [15,20+$this->SEsize,23 + $this->SEsize],
                ];

                $rows2 = [
                    [
                        10,
                        [1]
                    ],
                    [35,[5]],[15,[12,17+$this->SEsize]]
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

                // FONT SIZE TO 8
                $rows = ["A6:H" . (23 + $this->SEsize)];
                foreach($rows as $row){
                    $event->sheet->getDelegate()->getStyle($row)->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle($row)->getFont()->setSize(9);
                }
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
        $drawing->setCoordinates('C1');
        array_push($array, $drawing);

        if($this->data->doctor->doctor->signature){
            $temp = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $temp->setPath(public_path($this->data->doctor->doctor->signature));
            $temp->setResizeProportional(false);
            $temp->setHeight(35);
            $temp->setWidth(70);
            $temp->setOffsetX(20);
            $temp->setOffsetY(1);
            $temp->setCoordinates('A43');
            array_push($array, $temp);

            $temp2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $temp2->setPath(public_path($this->data->doctor->doctor->signature));
            $temp2->setResizeProportional(false);
            $temp2->setHeight(35);
            $temp2->setWidth(70);
            $temp2->setOffsetX(20);
            $temp2->setOffsetY(1);
            $temp2->setCoordinates('A46');
            array_push($array, $temp2);
        }

        return $array;
    }
}
