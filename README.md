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


API:

API decodes do json
- api/v1/decode_file [POST] - not checked probably wont work
- api/v1/prices [GET]
- api/v1/prices/city/{city} [GET]
- api/v1/prices/fuel/{fuel} [GET]
