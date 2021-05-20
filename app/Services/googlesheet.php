<?php

namespace App\Services;

use Google_Client;
use Google_Service;
use Google_Service_Sheets;

class googlesheet{

    private $google_sheet_id;
    private $client;
    private $google_servie;

    public function __construct()
    {
        $this->google_sheet_id = config('datastudio.google_api_sheet_id');
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('Credentials.json'));
        $this->client->addScope('https://googleapis.com/auth/spreadsheets');
        $this->google_servie = new Google_Service_Sheets($this->client);
    }

    public function readGoogleSheet()
    {
        
    }

}