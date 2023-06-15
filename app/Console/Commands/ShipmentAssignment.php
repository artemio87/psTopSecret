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
     * @return void
     */
    public function handle()
    {
        // Read input files path arguments
        $driversPath =  storage_path($this->argument('drivers'));
        $destinationsPath =  storage_path($this->argument('destinations'));

        // Read files contents
        $drivers = file($driversPath, FILE_IGNORE_NEW_LINES);
        $destinations = file($destinationsPath, FILE_IGNORE_NEW_LINES);

        // Declare vars to print the result
        $matchesDD = [];
        $totalSS = 0;

        // Iterate Destinations to calculate SS (suitability score) and find the best driver match for each destination
        foreach ($destinations as $destination) {
            $destinationLength = strlen($destination);
            $bestScore = 0;
            $bestMatch = '';

            // find the best Driver for Destination
            foreach ($drivers as $driver) {
                $driverLength = strlen($driver);

                // Verify is destination is even or odd with a module 2 to get the driver SS
                $baseScore = ($destinationLength % 2 === 0)
                    ? $this->countVowels($driver) * 1.5
                    : $this->countConsonants($driver);

                $score = $baseScore;

                // Check if Destination and Driver has common Factors
                if ($this->hasCommonFactors($destinationLength, $driverLength)) {
                    $score *= 1.5;
                }

                // Compare score with bestScore to define the best driver for current destination
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $driver;
                }
            }

            $totalSS += $bestScore;
            $matchesDD[$destination] = $bestMatch;
        }

        // Display the total SS and matching between destinations and drivers
        printf($totalSS);
        print("\n");
        print_r($matchesDD);
    }

    private function countVowels($string)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        return count(array_intersect(str_split(strtolower($string)), $vowels));
    }

    private function countConsonants($string)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        return count(array_diff(str_split(strtolower($string)), $vowels));
    }

    // Review if Destination and Driver has more than 1 factors in common
    // to make sure the point 3 of algorithm
    private function hasCommonFactors($destination, $driver)
    {
        $factorsDestination = $this->getFactors($destination);
        $factorsDriver = $this->getFactors($driver);

        return count(array_intersect($factorsDestination, $factorsDriver)) > 1;
    }

    // Get the Factor of a strlength
    private function getFactors($number)
    {
        $factors = [];
        for ($i = 2; $i <= $number / 2; $i++) {
            if ($number % $i === 0) {
                $factors[] = $i;
            }
        }
        return $factors;
    }
}
