# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

StellarWP Dates is a PHP library for WordPress that provides comprehensive date and time utilities. It's forked from The Events Calendar's battle-tested date handling functionality and serves as a community resource from StellarWP.

## Commands

### Development Commands
```bash
# Generate documentation from PHPDoc comments
composer create-docs

# Run static code analysis
composer test:analysis

# Install dependencies
composer install
```

### Testing
The project uses Codeception for testing. Tests are located in `tests/wpunit/` and require WordPress test environment setup. Test configuration is in `codeception.dist.yml` and `codeception.slic.yml`.

## Architecture

### Core Classes
- **`Dates`** (src/Dates/Dates.php): Main utility class with 70+ static methods for date operations
- **`Date_I18n`** (src/Dates/Date_I18n.php): Mutable DateTime extension with WordPress I18n support
- **`Date_I18n_Immutable`** (src/Dates/Date_I18n_Immutable.php): Immutable version of Date_I18n
- **`Timezones`** (src/Dates/Timezones.php): Timezone utilities integrated with WordPress

### Key Design Patterns
1. **All dates are built through `Dates::get()`** - This is the primary entry point for date creation
2. **Fallback handling** - Methods accept optional fallback values for invalid dates
3. **WordPress integration** - Deep integration with WordPress timezone and locale systems
4. **Immutability support** - Both mutable and immutable date objects available

### Important Constants
The library defines format constants in `Dates.php`:
- `DBDATEFORMAT` = 'Y-m-d'
- `DBDATETIMEFORMAT` = 'Y-m-d H:i:s'
- `DATEONLYFORMAT` = 'F j, Y'
- `TIMEFORMAT` = 'g:i a'

### Code Quality
- **PHPStan Level 3** with WordPress-specific rules
- Configuration in `phpstan.neon.dist`
- Ignores certain WordPress patterns (func_get_args usage)

## Development Notes

1. **Namespace**: All code under `StellarWP\Dates` namespace
2. **Installation**: Recommend using Strauss for namespace isolation to avoid conflicts
3. **Date Building**: Always use `Dates::get()` rather than direct instantiation
4. **Timezone Handling**: Library automatically falls back to WordPress timezone or UTC
5. **Testing**: Requires WordPress test environment setup for full test suite execution