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
}
