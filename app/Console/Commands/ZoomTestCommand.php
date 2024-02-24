<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZoomTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zoomtest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $service = new \App\Services\ZoomService();
        // dd($service->store("sample-update-test", "sample-agenda", 60, "2022-03-18 13:00"));
        // dd($service->update("84800094122", "sample-update-test22222", "sample-agenda222", 120, "2022-03-18 13:00"));
        // dd($service->destroy("81148447373"));
        // dd($service->detail("81021465991"));
        return 0;
    }
}
