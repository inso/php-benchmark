Usage
=====

Simple benchmark example, between the date() function and new DateTime::format() :

```php
<?php

require 'Benchmark.php';

$bench = new Benchmark;

$result = $bench
    ->setIterations(10000)
    ->setWarmUpIterations(1000)
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
 * array(2) {
 *   'function' =>
 *   array(2) {
 *     'time' =>
 *     double(0.05121898651123)
 *     'percent' =>
 *     int(100)
 *   }
 *   'object' =>
 *   array(2) {
 *     'time' =>
 *     double(0.074388027191162)
 *     'percent' =>
 *     int(145)
 *   }
 * }
 */
```
