# Microsoft SQL Server PHP Driver Locale Regression Fix

This repository demonstrates and provides a fix for a locale regression issue in the Microsoft SQL Server PHP drivers (`sqlsrv` and `pdo_sqlsrv`) that affects macOS systems.

## Issue Description

**GitHub Issue**: [microsoft/msphpsql#1532](https://github.com/microsoft/msphpsql/issues/1532)

When the `sqlsrv` or `pdo_sqlsrv` PHP extensions are loaded on macOS, they change the system locale settings, which affects the behavior of PHP's built-in locale-sensitive functions like `str_word_count()`.

### Symptoms

- `str_word_count('мама', 0, null)` returns `4` instead of the expected `0` when sqlsrv extension is loaded on macOS
- The issue is **macOS-specific** - Windows and Linux are not affected
- The problem occurs because the extension changes locale settings on load

### Root Cause

The sqlsrv extensions have built-in locale handling that automatically sets locale information based on the system environment. On macOS, where `LC_ALL` is typically set to `en_US.UTF-8`, this causes the extension to modify locale settings in a way that affects other PHP functions.

## Solution

Set the locale configuration options to prevent the extensions from modifying system locale settings:

### Option 1: PHP Configuration File

Add to your `php.ini` or a separate `.ini` file in your PHP configuration directory:

```ini
; Prevent sqlsrv extension from changing locale settings
sqlsrv.SetLocaleInfo = 0
pdo_sqlsrv.set_locale_info = 0
```

### Option 2: Runtime Configuration

Set the values programmatically before loading the extension:

```php
ini_set('sqlsrv.SetLocaleInfo', '0');
ini_set('pdo_sqlsrv.set_locale_info', '0');
```

### Option 3: Environment-Specific Configuration

For development environments using tools like Homebrew PHP or Laravel Valet:

```bash
# Add to your shell profile (.bashrc, .zshrc, etc.)
export PHPRC="/path/to/your/custom/php.ini"
```

## Configuration Values

The locale configuration options accept three values:

- **`0`** - Don't set any locale information (recommended to avoid side effects)  
- **`1`** - Set locale to `LC_ALL` when the driver loads
- **`2`** - Set locale to environment `LC_ALL` value

**Recommendation**: Use `0` to prevent any locale modifications unless you specifically need the extension to manage locale settings.

## Testing

This repository includes tests that verify the fix works correctly:

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/phpunit
```

The tests check:
1. `str_word_count()` behavior with Cyrillic text
2. Locale configuration settings
3. Extension loading status

## References

- [Microsoft Documentation: Non-System Locale Settings](https://learn.microsoft.com/en-us/sql/connect/php/non-system-locale-settings?view=sql-server-ver17)
- [Microsoft PHP SQL Server Drivers GitHub](https://github.com/microsoft/msphpsql)
- [Original Issue Report](https://github.com/microsoft/msphpsql/issues/1532)
- [Locale Configuration PR #1069](https://github.com/microsoft/msphpsql/pull/1069)

## Contributing

If you find additional locale-related issues or have improvements to this fix, please open an issue or pull request.