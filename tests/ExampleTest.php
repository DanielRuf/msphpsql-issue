<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_example()
    {
        echo ini_get('pdo_sqlsrv.set_locale_info') . PHP_EOL;
        echo 'LC_ALL: '.LC_ALL.PHP_EOL;
        echo 'sqlsrv: '.(extension_loaded('sqlsrv') ? 'loaded' : 'not loaded').PHP_EOL;

        $this->assertEquals(0, str_word_count('мама', 0, null));
    }

    public function test_locale_regression_issue()
    {
        // Test for GitHub issue microsoft/msphpsql#1532
        // The sqlsrv extension on macOS changes locale settings which affects str_word_count
        // This test should pass regardless of sqlsrv being loaded when proper configuration is used
        
        $test_string = 'мама'; // Russian for "mama" - Cyrillic characters
        $result = str_word_count($test_string, 0, null);
        
        echo "Testing str_word_count('$test_string', 0, null)\n";
        echo "Current locale: " . setlocale(LC_ALL, 0) . "\n";
        echo "Result: $result\n";
        echo "Expected: 0\n";
        
        // On proper configuration, this should return 0 regardless of sqlsrv being loaded
        $this->assertEquals(0, $result, 
            'str_word_count should return 0 for Cyrillic text when no custom locale is set. ' .
            'If this fails on macOS with sqlsrv loaded, check that pdo_sqlsrv.set_locale_info=0 is set.');
    }
}
