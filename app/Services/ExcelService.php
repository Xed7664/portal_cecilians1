<?php
// app/Services/ExcelService.php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ExcelService
{
    public static function readHeaders($file)
    {
        $headings = (new HeadingRowImport)->toArray($file);
        return array_map('trim', $headings[0][0]); // Return the headers as an array
    }
}
