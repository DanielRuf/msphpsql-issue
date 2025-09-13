<?php

echo "PHP Version: " . PHP_VERSION . "\n";
echo "OS: " . PHP_OS . "\n";
echo "LC_ALL env: " . getenv('LC_ALL') . "\n";
echo "LC_ALL constant: " . LC_ALL . "\n";
echo "Current locale: " . setlocale(LC_ALL, 0) . "\n";

// Test the word count behavior
$test_string = 'мама';  // Russian for "mama"
$word_count = str_word_count($test_string, 0, null);

echo "Testing str_word_count('$test_string', 0, null)\n";
echo "Result: $word_count\n";

// Check if sqlsrv is loaded
echo "sqlsrv extension loaded: " . (extension_loaded('sqlsrv') ? 'yes' : 'no') . "\n";
echo "pdo_sqlsrv extension loaded: " . (extension_loaded('pdo_sqlsrv') ? 'yes' : 'no') . "\n";

// Check sqlsrv ini settings if available
if (extension_loaded('pdo_sqlsrv')) {
    echo "pdo_sqlsrv.set_locale_info: " . ini_get('pdo_sqlsrv.set_locale_info') . "\n";
}

// Test with different locales manually
echo "\nTesting with different locales:\n";

$locales_to_test = ['C', 'C.UTF-8', 'en_US.UTF-8', 'ru_RU.UTF-8'];

foreach ($locales_to_test as $locale) {
    $old_locale = setlocale(LC_ALL, $locale);
    if ($old_locale !== false) {
        $count = str_word_count($test_string, 0, null);
        echo "Locale $locale: count = $count\n";
    } else {
        echo "Locale $locale: not available\n";
    }
}

// Reset to original locale
setlocale(LC_ALL, "C.UTF-8");