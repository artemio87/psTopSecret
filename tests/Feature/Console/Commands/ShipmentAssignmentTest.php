<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ShipmentAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use ReflectionMethod;
use Tests\TestCase;

class ShipmentAssignmentTest extends TestCase
{

    /*
     * Test if drivers file name is correct
     * */
    /** @test */
    public function it_drivers_file_is_not_correct()
    {
        // Run the command
        $this->artisan('shipment:assignment', [
            'drivers' => 'SSfiles/test_drives.tt',
            'destinations' => 'SSfiles/test_addresses.txt',
        ])->expectsOutput("Please Check your if input files are correct can be add '-h' after command to give more information")
            ->expectsOutput("The file name does not contain the word 'drivers.txt'. Execution stopped.")
            ->assertExitCode(0);
    }

    /*
     * Test if addresses file name is correct
     * */
    /** @test */
    public function it_addresses_file_is_not_correct()
    {
        // Run the command
        $this->artisan('shipment:assignment', [
            'drivers' => 'SSfiles/test_drivers.txt',
            'destinations' => 'SSfiles/test_addres.txt',
        ])->expectsOutput("Please Check your if input files are correct can be add '-h' after command to give more information")
            ->expectsOutput("The file name does not contain the word 'addresses.txt'. Execution stopped.")
            ->assertExitCode(0);
    }

    /*
     * Test to normal Usage
     * */
    /** @test */
    public function it_assigns_shipment_addresses_to_drivers_normal()
    {
        // Set up test files
        $driversFilePath = storage_path('SSfiles/test_drivers.txt');
        $destinationsFilePath = storage_path('SSfiles/test_addresses.txt');
        $driversContent = "Testerino McDonald\nText Jackson\nDionicio Tester\n";
        $destinationsContent = "1234 Fake St., San Diego, CA 92126\n1797 Adolf Island Apt. 744, San Diego, CA 92126\n987 Champlin Lake, San Diego, CA 92126\n";
        file_put_contents($driversFilePath, $driversContent);
        file_put_contents($destinationsFilePath, $destinationsContent);

        $expectedTable = [
            ['1234 Fake St., San Diego, CA 92126', 'Testerino McDonald'],
            ['1797 Adolf Island Apt. 744, San Diego, CA 92126', 'Dionicio Tester'],
            ['987 Champlin Lake, San Diego, CA 92126', 'Text Jackson'],
        ];

        // Run the command
        $this->artisan('shipment:assignment', [
            'drivers' => 'SSfiles/test_drivers.txt',
            'destinations' => 'SSfiles/test_addresses.txt',
        ])->expectsOutput('Total Suitability Score: 28.25')
            ->expectsTable(['Destination', 'Driver'], $expectedTable)
            ->assertExitCode(0);

        // Clean up test files
        unlink($driversFilePath);
        unlink($destinationsFilePath);
    }

    /*
     * When have more drivers than addresses
     * */
    /** @test */
    public function it_have_more_drivers_than_addresses()
    {
        // Set up test files
        $driversFilePath = storage_path('SSfiles/test_drivers.txt');
        $destinationsFilePath = storage_path('SSfiles/test_addresses.txt');
        $driversContent = "Testerino McDonald\nText Jackson\nDionicio Tester\nLuca Chan\n";
        $destinationsContent = "1234 Fake St., San Diego, CA 92126\n1797 Adolf Island Apt. 744, San Diego, CA 92126\n987 Champlin Lake, San Diego, CA 92126\n";
        file_put_contents($driversFilePath, $driversContent);
        file_put_contents($destinationsFilePath, $destinationsContent);

        $expectedTable = [
            ['1234 Fake St., San Diego, CA 92126', 'Testerino McDonald'],
            ['1797 Adolf Island Apt. 744, San Diego, CA 92126', 'Dionicio Tester'],
            ['987 Champlin Lake, San Diego, CA 92126', 'Text Jackson'],
        ];

        // Run the command
        $this->artisan('shipment:assignment', [
            'drivers' => 'SSfiles/test_drivers.txt',
            'destinations' => 'SSfiles/test_addresses.txt',
        ])->expectsOutput('Total Suitability Score: 28.25')
            ->expectsTable(['Destination', 'Driver'], $expectedTable)
            ->expectsOutput('Drivers available to assign to new destination:')
            ->expectsOutput('Luca Chan')
            ->assertExitCode(0);

        // Clean up test files
        unlink($driversFilePath);
        unlink($destinationsFilePath);
    }

    /*
     * When have more addresses than drivers
     * */
    /** @test */
    public function it_have_more_addresses_than_drivers()
    {
        // Set up test files
        $driversFilePath = storage_path('SSfiles/test_drivers.txt');
        $destinationsFilePath = storage_path('SSfiles/test_addresses.txt');
        $driversContent = "Testerino McDonald\nText Jackson\nDionicio Tester\n";
        $destinationsContent = "1234 Fake St., San Diego, CA 92126\n1797 Adolf Island Apt. 744, San Diego, CA 92126\n987 Champlin Lake, San Diego, CA 92126\n75855 Dessie Lights, San Diego, CA 92126\n";
        file_put_contents($driversFilePath, $driversContent);
        file_put_contents($destinationsFilePath, $destinationsContent);

        $expectedTable = [
            ['1234 Fake St., San Diego, CA 92126', 'Testerino McDonald'],
            ['1797 Adolf Island Apt. 744, San Diego, CA 92126', 'Dionicio Tester'],
            ['987 Champlin Lake, San Diego, CA 92126', 'Text Jackson'],
        ];

        // Run the command
        $this->artisan('shipment:assignment', [
            'drivers' => 'SSfiles/test_drivers.txt',
            'destinations' => 'SSfiles/test_addresses.txt',
        ])->expectsOutput('Total Suitability Score: 28.25')
            ->expectsTable(['Destination', 'Driver'], $expectedTable)
            ->expectsOutput('Addresses that cannot be assigned today:')
            ->expectsOutput('75855 Dessie Lights, San Diego, CA 92126')
            ->assertExitCode(0);

        // Clean up test files
        unlink($driversFilePath);
        unlink($destinationsFilePath);
    }

    /*
     * Test countVowels
     * */
    /** @test
     * @throws \ReflectionException
     */
    public function it_counts_vowels_in_a_string()
    {
        $command = new ShipmentAssignment();
        $string = 'Artemio Rodriguez';

        $reflectionMethod = new ReflectionMethod(ShipmentAssignment::class, 'countVowels');

        $result = $reflectionMethod->invoke($command, $string);

        $this->assertEquals(8, $result);
    }

    /*
     * Test countConsonants
     * */
    /** @test
     * @throws \ReflectionException
     */
    public function it_counts_consonants_in_a_string()
    {
        $command = new ShipmentAssignment();
        $string = 'Artemio Rodriguez';

        $reflectionMethod = new ReflectionMethod(ShipmentAssignment::class, 'countConsonants');

        $result = $reflectionMethod->invoke($command, $string);

        $this->assertEquals(8, $result);
    }

    /*
     * Test hasCommonFactors
     * */
    /** @test
     * @throws \ReflectionException
     */
    public function it_checks_if_address_and_driver_have_common_factors()
    {
        $command = new ShipmentAssignment();
        $destination = 20;
        $driver = 8;

        $reflectionMethod = new ReflectionMethod(ShipmentAssignment::class, 'hasCommonFactors');

        $result = $reflectionMethod->invoke($command, $destination, $driver);

        $this->assertTrue($result);
    }

    /*
     * Test getFactors
     * */
    /** @test
     * @throws \ReflectionException
     */
    public function it_gets_factors_of_a_number()
    {
        $command = new ShipmentAssignment();
        $number = 20;

        $reflectionMethod = new ReflectionMethod(ShipmentAssignment::class, 'getFactors');

        $result = $reflectionMethod->invoke($command, $number);

        $this->assertEquals([2, 4, 5, 10], $result);
    }

    /*
     * Test getStreetLength
     * */
    /** @test
     * @throws \ReflectionException
     */
    public function it_gets_street_length()
    {
        $command = new ShipmentAssignment();
        $string = '1797 Adolf Island Apt. 744, San Diego, CA 92126';

        $reflectionMethod = new ReflectionMethod(ShipmentAssignment::class, 'getStreetLength');

        $result = $reflectionMethod->invoke($command, $string);

        $this->assertEquals(15, $result);
    }
}
