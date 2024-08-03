<?php
namespace App\Helpers;
// use App\Services\Fcm;
use Maatwebsite\Excel\Facades\Excel;
class ExcelHelper {

    public static function DownloadTemplate($headers)
    {
        // $data = [
        //     ['No', 'Kode MK', 'Nama MK', 'Kelas', 'NIP', 'Nama', 'Paralel'],
        // ];

        return Excel::download(new class($headers) implements FromCollection, WithEvents {
            use Exportable;

            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return collect($this->data);
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function(AfterSheet $event) {
                        // Atur style header
                        $event->sheet->getStyle('A1:G1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFCCCCCC',
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);
                    },
                ];
            }
        }, $template_name ? $template_name : 'template' . '.xlsx');
    }

    public static function readDataExcel($file, $rowHeader)
    {
        $data = Excel::toArray([], $file);

        $excelHeaders = array_filter($data[0][$rowHeader - 1], function($value) {
            return $value !== null;
        });

        $dataNilaiMahasiswa = [];

        foreach ($data[0] as $rowIndex => $row) {
            if (self::isHeaderRow($row, $excelHeaders)) {
                $headers = $row;
                $headerRowIndex = $rowIndex;
                break;
            }
        }

        foreach ($data[0] as $rowIndex => $row) {
            if ($rowIndex <= $headerRowIndex) {
                continue; 
            }

            $mappedRow = [];
            foreach ($excelHeaders as $header) {
                $mappedRow[$header] = $row[array_search($header, $excelHeaders)] ?? null;
            }

            $dataNilaiMahasiswa[] = $mappedRow;
        }

        return $dataNilaiMahasiswa;
    }

    private static function isHeaderRow($row, $excelHeaders)
    {
        foreach ($excelHeaders as $header) {
            if (in_array($header, $row)) {
                return true;
            }
        }
        return false;
    }
}