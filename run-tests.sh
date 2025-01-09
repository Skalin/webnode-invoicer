#!/bin/sh

BASEDIR=$(dirname "$0")
cd $BASEDIR

final_exit_code=0

echo
echo "########################################"
echo "########  PHP STATIC ANALYSIS  #########"
echo "########################################"
echo
php vendor/bin/phpstan analyze --memory-limit=-1 -c ./phpstan.dist.neon
final_exit_code=$(($final_exit_code+$?))



if [ $final_exit_code -ne 0 ]; then
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!  PHPSTAN ERROR  !!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    exit $final_exit_code
else
    echo "++++++++++++++++++++++++++++++++++++++++++++++++"
    echo "++++++++++++++  PHPSTAN SUCCESS  +++++++++++++++"
    echo "++++++++++++++++++++++++++++++++++++++++++++++++"
fi

./vendor/bin/phpcbf ./src
final_exit_code=$(($final_exit_code+$?))

if [ $final_exit_code -ne 0 ]; then
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!  CS ERROR  !!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
else
    echo "+++++++++++++++++++++++++++++++++++++++++++"
    echo "++++++++++++++  CS SUCCESS  +++++++++++++++"
    echo "+++++++++++++++++++++++++++++++++++++++++++"
fi


php bin/phpunit
final_exit_code=$(($final_exit_code+$?))

if [ $final_exit_code -ne 0 ]; then
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!  PHPUNIT ERROR  !!!!!!!!!!!!!!!!"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
else
    echo "+++++++++++++++++++++++++++++++++++++++++++"
    echo "++++++++++++++  PHPUNIT SUCCESS  +++++++++++++++"
    echo "+++++++++++++++++++++++++++++++++++++++++++"
fi
exit $final_exit_code
