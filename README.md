# Command TPSS(Top Secret suitability scores)

This command use the top-secret algorithm called TPSS (by me) that assigns shipment destinations to drivers based on suitability scores (SS).

### Algorithm bases
```text
- If the length of the shipment's destination street name is even, the base suitability score (SS) is the number of vowels in the driver’s
name multiplied by 1.5.

- If the length of the shipment's destination street name is odd, the base SS is the number of consonants in the driver’s name multiplied by 1.

- If the length of the shipment's destination street name shares any common factors (besides 1) with the length of the driver’s name, the
SS is increased by 50% above the base SS.
```
## Installation

1. Clone the repository or download the source code.
2. Make sure you have installed `PHP:8.1` or higher and `Composer` on your computer.
3. Run `composer install` to install the dependencies.

## Usage

The command expects two input files: `drivers.txt` and `addresses.txt`. Make sure the input files are correctly formatted before running the command.

To execute the command, open your terminal and navigate to the project directory. Then run the following command:

```shell
php artisan shipment:assign
```
**NOTE:** Command use storage folder you should save the files that you want to use.

By default, the command will look for the input files `storage/SSfiles/drivers.txt` and `storage/SSfiles/addresses.txt` in the project storage directory .

If you have the input files in a different location or with a different name, you can specify the file paths using the following command: 

```shell
php artisan shipment:assignment SSfiles/drivers.txt SSfiles/addresses.txt 
```
If you need help or more information about the command, you can use the -h option

```shell
php artisan shipment:assign -h
```

## Input File Format
The input files should be in plain text format. Here are the required formats for the input files:

### drivers.txt
The drivers.txt file should contain a list of driver names, with each name on a separate line. For example:

```text
John Doe
Jane Smith
Robert Johnson
```

### addresses.txt
The addresses.txt file should contain a list of destination addresses, with each address on a separate line. For example:

```text
1234 Fake St., San Diego, CA 92126
1797 Adolf Island Apt. 744, San Diego, CA 92126
987 Champlin Lake, San Diego, CA 92126
```
## Output
After running the command, it will display the assignment of destinations to drivers, along with their suitability scores. The output will be in the following format:
```text
Total Suitability Score: 21.75
+-------------------------------------------------+----------------+
| Destination                                     | Driver         |
+-------------------------------------------------+----------------+
| 1234 Fake St., San Diego, CA 92126              | Robert Johnson |
| 1797 Adolf Island Apt. 744, San Diego, CA 92126 | Jane Smith     |
| 987 Champlin Lake, San Diego, CA 92126          | John Doe       |
+-------------------------------------------------+----------------+
```
## Extra Features
The command has some extra features to try including a couple of files in `storage/SSfiles/`. 
These features consist of a couple of cases that I list now:

### More Drivers than Destinations

To execute this case, run the following command:

```shell
php artisan shipment:assignment SSfiles/15-drivers.txt SSfiles/addresses.txt 
```
The output will be in the following format:

```text
Total Suitability Score: 95.75
+-----------------------------------------------------+-------------------+
| Destination                                         | Driver            |
+-----------------------------------------------------+-------------------+
| 215 Osinski Manors, San Diego, CA 92126             | Murphy Mosciski   |
| 9856 Marvin Stravenue, San Diego, CA 92126          | Howard Emmerich   |
| 7127 Kathlyn Ferry, San Diego, CA 92126             | Artemio Rodriguez |
| 987 Champlin Lake, San Diego, CA 92126              | Anakin Skywalker  |
| 63187 Volkman Garden Suite 447, San Diego, CA 92126 | Orval Mayert      |
| 75855 Dessie Lights, San Diego, CA 92126            | Izaiah Lowe       |
| 1797 Adolf Island Apt. 744, San Diego, CA 92126     | Everardo Welch    |
| 2431 Lindgren Corners, San Diego, CA 92126          | Monica Hermann    |
| 8725 Aufderhar River Suite 859, San Diego, CA 92126 | Harry Potter      |
| 1234 Fake St., San Diego, CA 92126                  | Ellis Wisozk      |
+-----------------------------------------------------+-------------------+
Drivers available to assign to new destination:
Noemie Murphy
Cleve Durgan
Kaiser Sose

```

### More Destinations than Drivers

To execute this case, run the following command:

```shell
php artisan shipment:assignment SSfiles/drivers.txt SSfiles/13-addresses.txt 
```
The output will be in the following format:

```text
Total Suitability Score: 87.5
+-----------------------------------------------------+-----------------+
| Destination                                         | Driver          |
+-----------------------------------------------------+-----------------+
| 215 Osinski Manors, San Diego, CA 92126             | Murphy Mosciski |
| 9856 Marvin Stravenue, San Diego, CA 92126          | Howard Emmerich |
| 7127 Kathlyn Ferry, San Diego, CA 92126             | Orval Mayert    |
| 987 Champlin Lake, San Diego, CA 92126              | Izaiah Lowe     |
| 63187 Volkman Garden Suite 447, San Diego, CA 92126 | Ellis Wisozk    |
| 75855 Dessie Lights, San Diego, CA 92126            | Cleve Durgan    |
| 1797 Adolf Island Apt. 744, San Diego, CA 92126     | Everardo Welch  |
| 2431 Lindgren Corners, San Diego, CA 92126          | Monica Hermann  |
| 8725 Aufderhar River Suite 859, San Diego, CA 92126 | Noemie Murphy   |
| 1234 Fake St., San Diego, CA 92126                  | Kaiser Sose     |
+-----------------------------------------------------+-----------------+
Addresses that cannot be assigned today:
2101 KETTNER BLVD, SAN DIEGO, CA 92101
1801 DIAMOND ST UNIT 310, SAN DIEGO, CA 92109
2701 ELM AVE, SAN DIEGO, CA 92154
```

## Test

The project has some tests to check the operation of the command to execute it use the following command:

```shell
php artisan test
```

