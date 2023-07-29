<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\BlastContact;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class BlastContactExport implements FromCollection, WithColumnWidths, WithColumnFormatting, WithMapping
{
    protected $data;

    function __construct($data) {
            $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function columnFormats(): array
    {
        return [
            "B" => NumberFormat::FORMAT_TEXT,
        ];
    }

     public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 35,
            'C' => 25,
            'D' => 45,
        ];
    }

    public function map($blastcontact): array
    {
        return [
            $blastcontact->Name,
            (string) $blastcontact->Number,
            $blastcontact->Status,
            $blastcontact->Device,
        ];
    }
}
