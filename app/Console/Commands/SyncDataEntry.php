<?php

namespace App\Console\Commands;

use App\Models\data_entry;
use App\Models\variable;
use App\Services\googlesheet;
use Illuminate\Console\Command;

class SyncDataEntry extends Command
{
    protected $signature = 'sync:entries';

    protected $description = 'this command will sync new entries in DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(googlesheet $googlesheet)
    {
        $variable = variable::where('name', 'lastSyncId')->first();
        $row = data_entry::where('id', '>', $variable->value)
            ->orderBy('id')
            ->limit(10)
            ->get();

        if ($row === 0) {
            return true;
        }

        $data = collect();
        $lastid = 0;

        foreach ($row as $items ) {
            $data->push([
                $items->id,
                $items->username,
                $items->course_name,
                $items->time,
            ]);
            $lastid = $items->id;
        }

        $googlesheet->saveDataToSheet($data->toArray());
        $variable->update(['value'=>$lastid]);
        return true;
    }
}
