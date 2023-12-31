<?php

namespace App\Listeners;

use App\Events\LogFilled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Revolution\Google\Sheets\Facades\Sheets;

class SendDataLogToSpreadsheet implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */

    public $tries = 5;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LogFilled $event): void
    {
        $logData = $event->logData;
        $sheet = Sheets::spreadsheet(env('SPREADSHEET_ID'));
        $sheet->sheet('data_log')->append([$logData]);
    }
}
