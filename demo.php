#!/usr/bin/env php
<?php
/**
 * Demonstration script for Microsoft SQL Server PHP Driver Locale Regression Issue
 * 
 * This script demonstrates the locale regression issue where str_word_count()
 * returns incorrect results when sqlsrv extension is loaded on macOS.
 * 
 * Issue: https://github.com/microsoft/msphpsql/issues/1532
 */

echo "=== Microsoft SQL Server PHP Driver Locale Regression Demonstration ===\n\n";

// Display environment information
echo "Environment Information:\n";
echo "  PHP Version: " . PHP_VERSION . "\n";
echo "  Operating System: " . PHP_OS . "\n";
echo "  Current Locale: " . setlocale(LC_ALL, 0) . "\n";
echo "  LC_ALL Environment: " . (getenv('LC_ALL') ?: 'not set') . "\n";
echo "  LC_ALL Constant: " . LC_ALL . "\n\n";

// Check extension status
echo "Extension Status:\n";
echo "  sqlsrv loaded: " . (extension_loaded('sqlsrv') ? 'yes' : 'no') . "\n";
echo "  pdo_sqlsrv loaded: " . (extension_loaded('pdo_sqlsrv') ? 'yes' : 'no') . "\n\n";

// Check configuration
echo "Configuration:\n";
if (extension_loaded('sqlsrv')) {
    echo "  sqlsrv.SetLocaleInfo: " . ini_get('sqlsrv.SetLocaleInfo') . "\n";
}
if (extension_loaded('pdo_sqlsrv')) {
    echo "  pdo_sqlsrv.set_locale_info: " . ini_get('pdo_sqlsrv.set_locale_info') . "\n";
}
echo "\n";

// Test the problematic function
echo "Testing str_word_count() with Cyrillic text:\n";
$test_cases = [
    'мама' => 'Russian for "mama"',
    'привет' => 'Russian for "hello"',
    'тест' => 'Russian for "test"',
    'hello world' => 'English text for comparison'
];

foreach ($test_cases as $text => $description) {
    $count = str_word_count($text, 0, null);
    echo "  str_word_count('$text', 0, null) = $count  // $description\n";
}

echo "\n";

// Expected behavior
echo "Expected Behavior:\n";
echo "  - Cyrillic text should return 0 (no words counted)\n";
echo "  - English text should return actual word count\n";
echo "  - Results should be consistent regardless of sqlsrv extension being loaded\n\n";

// Issue explanation
echo "Known Issue:\n";
echo "  On macOS with sqlsrv extension loaded and default configuration:\n";
echo "  - str_word_count('мама', 0, null) returns 4 instead of 0\n";
echo "  - This happens because sqlsrv changes locale settings on load\n\n";

// Solution
echo "Solution:\n";
echo "  Set these configuration values to prevent locale changes:\n";
echo "    sqlsrv.SetLocaleInfo = 0\n";
echo "    pdo_sqlsrv.set_locale_info = 0\n\n";

// Runtime check
if (extension_loaded('sqlsrv') || extension_loaded('pdo_sqlsrv')) {
    $sqlsrv_config = ini_get('sqlsrv.SetLocaleInfo');
    $pdo_config = ini_get('pdo_sqlsrv.set_locale_info');
    
    if ($sqlsrv_config === '0' && $pdo_config === '0') {
        echo "✅ Configuration looks correct - locale issue should be prevented!\n";
    } else {
        echo "⚠️  Warning: Configuration may not prevent locale issues.\n";
        echo "   Current settings: sqlsrv.SetLocaleInfo=$sqlsrv_config, pdo_sqlsrv.set_locale_info=$pdo_config\n";
        echo "   Recommended: both should be set to 0\n";
    }
} else {
    echo "ℹ️  No SQL Server PHP extensions loaded - no configuration needed.\n";
}

echo "\nFor more information, see: https://github.com/microsoft/msphpsql/issues/1532\n";