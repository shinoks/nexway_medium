**Medium test for NexWay**

Sample files in different format are located in **tests/sample/**

Lib decodes :
- json
- csv
- xml
- html
- from db table

App\Utils\ImportPrices 
->import(File $file)

to simple array ready to use:


    [
        city => [fuelname = price, fuelname2 = price, fuelname3 = price, ... ],
        city2 => [fuelname = price, fuelname2 = price, fuelname3 = price, ... ],
    ]
