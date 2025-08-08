<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_example()
    {
        echo 'LC_ALL: '.LC_ALL;
        $this->assertEquals(0, str_word_count('мама', 0, null));
    }
}