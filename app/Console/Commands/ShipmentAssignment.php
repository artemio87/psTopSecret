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
    protected $signature = 'shipment:assignment {drivers=SSfiles\drivers.txt : path/drivers_file.txt} {destinations=SSfiles\addresses.txt : path/addresses_file.txt}';

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

        // Check if file name contain the word drivers and destinations/addresses to make sure put correct file name
        // and the files are set in correct argument position

        if (!str_contains(basename($driversPath), "drivers.txt")) {
            $this->warn("Please Check your if input files are correct can be add '-h' after command to give more information");
            $this->error("The file name does not contain the word 'drivers.txt'. Execution stopped.");
            return;
        }

        if (!str_contains(basename($destinationsPath), "addresses.txt")) {
            $this->warn("Please Check your if input files are correct can be add '-h' after command to give more information");
            $this->error("The file name does not contain the word 'addresses.txt'. Execution stopped.");
            return;
        }

        // Read files contents
        $drivers = file($driversPath, FILE_IGNORE_NEW_LINES);
        $destinations = file($destinationsPath, FILE_IGNORE_NEW_LINES);

        // Make a copy of drivers
        $availableDrivers = $drivers;

        // Declare vars to print the result
        $matchesDD = [];
        $totalSS = 0;

        // Iterate Destinations to calculate SS (suitability score) and find the best driver match for each destination
        foreach ($destinations as $destination) {
            $destinationLength = $this->getStreetLength($destination);
            $bestScore = 0;
            $bestMatch = '';

            // find the best Driver for Destination
            foreach ($availableDrivers as $driver) {
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

            // Remove assigned driver
            $availableDrivers = array_values(array_diff($availableDrivers, [$bestMatch]));
        }

        // Display the total SS and matching between destinations and drivers (Pretty Way)
        $this->info("Total Suitability Score: $totalSS");
        $this->table(['Destination', 'Driver'], array_map(fn ($destination, $driver) => [$destination, $driver], array_keys($matchesDD), $matchesDD));

        // Check if there are more addresses for which there are no drivers available
        if (count($destinations) > count($drivers)) {
            $unshippedDestinations = array_slice($destinations, count($drivers));
            $this->info("Addresses that cannot be assigned today:");
            $this->info(implode("\n", $unshippedDestinations));
        }

        // Check for drivers that were not assigned to destination
        if (!empty($availableDrivers)) {
            $this->info("Drivers available to assign to new destination:");
            $this->info(implode("\n", $availableDrivers));
        }
    }

    private function countVowels($string)
    {
        // Regular expression '/[aeiou]/i'  to find vowels in the string
        preg_match_all('/[aeiou]/i', $string, $matches);
        return count($matches[0]);
    }

    private function countConsonants($string)
    {
        // Regular expression '/[^aeiou]/i' adding ^ to find all letters that is not a vowel in the string
        $consonantsCount = preg_match_all('/[^aeiou]/i', $string);
        return $consonantsCount;
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

    // Get Street Length
    private function getStreetLength($string) {
        // Remove numbers and spaces from the string
        $cleanString = preg_replace('/[0-9\s]+/', '', $string);

        // Find the length of the string before the first comma
        $streetLength = strpos($cleanString, ',');

        return $streetLength;
    }
}
