Usage
=====

Simple benchmark example, between the date() function and new DateTime::format() :

```php
<?php

require 'Benchmark.php';

$bench = new Benchmark;

$result = $bench
    ->addTarget('function', function() {
        return date('Y-m-d');
    })
    ->addTarget('object', function() {
        $date = new DateTime;
        return $date->format('Y-m-d');
    })
    ->warmUp()
    ->execute()
    ->getResults()
;

/*
 * var_dump($result) :
 *
 *   array (size=2)
 *     'function' => string '100%' (length=4)
 *     'object'   => string '122%' (length=4) 
 */
```