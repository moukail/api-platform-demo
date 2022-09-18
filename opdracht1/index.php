<?php

for ($i= 1; $i<=30; $i++){

    if (isModulo($i, 3*5)){
        echo "FizzBuzz" . PHP_EOL;
        continue;
    }

    if ($i % 3 == 0){
        echo "Fizz" . PHP_EOL;
        continue;
    }

    if ($i % 5 == 0){
        echo "Buzz" . PHP_EOL;
        continue;
    }

    echo $i . PHP_EOL;
}

function isModulo($x, $y)
{
    return $x % $y == 0;
}
