<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShipmentAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment:assignment {drivers : path/drivers_file.txt} {destinations : path/destinations_file.txt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for assign shipment destinations to drivers based on suitability scores (SS), can happen using the TOP SECRET Platform Science algorithm';

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
        // Read input files path arguments
        $driversPath =  storage_path($this->argument('drivers'));
        $destinationsPath =  storage_path($this->argument('destinations'));

        // Read files contents
        $drivers = file($driversPath, FILE_IGNORE_NEW_LINES);
        $destinations = file($destinationsPath, FILE_IGNORE_NEW_LINES);

        // Print Array From .txt files
        print_r($drivers);
        print_r($destinations);
        return 0;
    }
}
