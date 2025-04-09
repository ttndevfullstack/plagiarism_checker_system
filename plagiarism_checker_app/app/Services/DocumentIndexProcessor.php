<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class DocumentIndexProcessor
{
    public function processIndexFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $fileIndex = [];

        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            
            if (!empty($rowData[0])) {
                $fileIndex[$rowData[0]] = [
                    'subject_code' => $rowData[1] ?? null,
                    'category' => $rowData[2] ?? null,
                    'description' => $rowData[3] ?? null
                ];
            }
        }

        return $fileIndex;
    }
}
