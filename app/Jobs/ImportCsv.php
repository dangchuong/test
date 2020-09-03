<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;
use \App\Models\TmpCsv;
use \App\Models\History;

class ImportCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csv;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TmpCsv $csv)
    {
        $this->csv = $csv;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (($handle = fopen(public_path($this->csv->path), "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if ($row > 1) {
                    History::create([
                        'provider_id' => $data[1],
                        'order_id' => $data[3],
                        'visibility' => $data[5],
                    ]);
                }
            }
            fclose($handle);
        }
    }
}
