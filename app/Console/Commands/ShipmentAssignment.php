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
    protected $signature = 'shipment:assignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command allows you to assign shipping destinations to drivers in a way that maximizes the total SS over the set of drivers, can happen using the TOP SECRET Platform Science algorithm';

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
        print_r('Hello From new php artisan Command');
        return 0;
    }
}
