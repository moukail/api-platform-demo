<?php

$begin = new DateTime('2022-07-28');
$end = new DateTime('2022-11-03');

$dayOfFirstWeek = $begin->format('w');
$dayOfLastWeek = $end->format('w');

// Lets begin with monday
$interval = DateInterval::createFromDateString('+'.(7+1 - $dayOfFirstWeek) . ' day');
$begin->add($interval);
echo 'Start: ' . $begin->format('l Y-m-d'). PHP_EOL;

$interval = DateInterval::createFromDateString('-'. $dayOfLastWeek . ' day');
$end->add($interval);
echo 'End: ' . $end->format('l Y-m-d') . PHP_EOL;

$interval = DateInterval::createFromDateString('next monday');
$period = new DatePeriod($begin, $interval, $end);

/** @var DateTime $dt */
foreach ($period as $dt) {
    echo $dt->format('l Y-m-d'), "\n";
}