<?php

namespace App\Services;

use Google_Client;
use Google_Service;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;

class googlesheet
{

    private $google_sheet_id;
    private $client;
    private $googleSheetService;

    public function __construct()
    {
        $this->google_sheet_id = config('datastudio.google_api_sheet_id');
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('credentials.json'));
        $this->client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $this->googleSheetService = new Google_Service_Sheets($this->client);
    }

    public function readGoogleSheet()
    {
        $dimension = $this->getDimensions($this->google_sheet_id);
        $range = 'Sheet1!A1:' . $dimension['colCount'];
        $data = $this->googleSheetService
            ->spreadsheets_values
            ->batchget($this->google_sheet_id, ['ranges' => $range]);

        return  $data->getValueRanges()[0]->values;
    }

    public function saveDataToSheet(array $data)
    {
        $dimension = $this->getDimensions($this->google_sheet_id);
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $data
        ]);
        $params = ['valueInputOption'=>'USER_ENTERED'];
        $range = "A". ($dimension['rowCount'] + 1);
        dd($this->googleSheetService->spreadsheets_values->update($this->google_sheet_id,$range,$body,$params));
    }

    private function getDimensions($spreadSheetId)
    {
        $rowDimensions = $this->googleSheetService->spreadsheets_values->batchGet(
            $spreadSheetId,
            ['ranges' => 'Sheet1!A:A', 'majorDimension' => 'COLUMNS']
        );

        //if data is present at nth row, it will return array till nth row
        //if all column values are empty, it returns null
        $rowMeta = $rowDimensions->getValueRanges()[0]->values;
        if (!$rowMeta) {
            return [
                'error' => true,
                'message' => 'missing row data'
            ];
        }

        $colDimensions = $this->googleSheetService->spreadsheets_values->batchGet(
            $spreadSheetId,
            ['ranges' => 'Sheet1!1:1', 'majorDimension' => 'ROWS']
        );

        //if data is present at nth col, it will return array till nth col
        //if all column values are empty, it returns null
        $colMeta = $colDimensions->getValueRanges()[0]->values;
        if (!$colMeta) {
            return [
                'error' => true,
                'message' => 'missing row data'
            ];
        }

        return [
            'error' => false,
            'rowCount' => count($rowMeta[0]),
            'colCount' => $this->colLengthToColumnAddress(count($colMeta[0]))
        ];
    }

    private  function colLengthToColumnAddress($number)
    {
        if ($number <= 0) return null;
        $letter = '';
        while ($number > 0) {
            $temp = ($number - 1) % 26;
            $letter = chr($temp + 65) . $letter;
            $number = ($number - $temp - 1) / 26;
        }
        return $letter;
    }
}
