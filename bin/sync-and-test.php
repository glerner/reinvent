<?php
/**
 * Sync Plugin to WordPress and Run Tests
 *
 * This script syncs your plugin to WordPress and runs PHPUnit tests.
 * It provides a simple way to run tests without requiring Composer or Lando command lines.
 *
 * Usage:
 *   php bin/sync-and-test.php [--unit|--wp-mock|--integration|--all] [--file=<file>] [--coverage] [--verbose]
 *
 * Examples:
 *   php bin/sync-and-test.php --unit
 *   php bin/sync-and-test.php --wp-mock --file=tests/wp-mock/specific-test.php
 *   php bin/sync-and-test.php --integration --coverage
 *   php bin/sync-and-test.php --all --verbose
 *
 * @package WP_PHPUnit_Framework
 */

// phpcs:set WordPress.Security.EscapeOutput customEscapingFunctions[] esc_cli
// phpcs:disable WordPress.WP.AlternativeFunctions
// phpcs:disable WordPress.DB.RestrictedFunctions
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedNamespaceFound

declare(strict_types=1);

namespace WP_PHPUnit_Framework\Bin;

/* Define script constants as namespace constants
 * SCRIPT_DIR should be your-plugin/tests/bin
 * PROJECT_DIR should be your-plugin
*/
define('PROJECT_DIR', dirname(__DIR__));
define('SCRIPT_DIR', PROJECT_DIR . '/tests/bin');
define('TESTS_DIR', PROJECT_DIR . '/tests');
define('PHPUNIT_FRAMEWORK_DIR', PROJECT_DIR . '/tests/gl-phpunit-test-framework');

// Include the framework utility functions
require_once PHPUNIT_FRAMEWORK_DIR . '/bin/framework-functions.php';

use function WP_PHPUnit_Framework\load_settings_file;
# use function WP_PHPUnit_Framework\get_phpunit_database_settings;
use function WP_PHPUnit_Framework\get_setting;
use function WP_PHPUnit_Framework\esc_cli;
use function WP_PHPUnit_Framework\is_lando_environment;

// Set default timezone to avoid warnings
date_default_timezone_set('UTC');

/**
 * Print a colored message to the console
 *
 * @param string $message The message to print
 * @param string $color   The color to use (green, yellow, red)
 * @return void
 */
function colored_message(string $message, string $color = 'normal'): void {
	$colors = [
		'green'  => "\033[0;32m",
		'yellow' => "\033[1;33m",
		'red'    => "\033[0;31m",
		'blue'   => "\033[0;34m",
		'normal' => "\033[0m",
	];

	$start_color = isset($colors[$color]) ? $colors[$color] : $colors['normal'];
	$end_color   = $colors['normal'];

	echo esc_cli($start_color . $message . $end_color . "\n");
}

/**
 * Print usage information
 *
 * @return void
 */
function print_usage(): void {
	colored_message("Usage:", 'blue');
	echo esc_cli("  php bin/sync-and-test.php [options] [--file=<file>]\n\n");

	colored_message("Options:", 'blue');
	echo esc_cli("  --help          Show this help message\n");
	echo esc_cli("  --unit          Run unit tests (tests that don't require WordPress functions)\n");
	echo esc_cli("  --wp-mock       Run WP Mock tests (tests that mock WordPress functions)\n");
	echo esc_cli("  --integration   Run integration tests (tests that require a WordPress database)\n");
	echo esc_cli("  --all           Run all test types\n");
	echo esc_cli("  --coverage      Generate code coverage report in build/coverage directory\n");
	echo esc_cli("  --verbose       Show verbose output\n");
	echo esc_cli("  --file=<file>   Run a specific test file instead of the entire test suite\n\n");

	colored_message("Examples:", 'blue');
	echo esc_cli("  php bin/sync-and-test.php --unit\n");
	echo esc_cli("  php bin/sync-and-test.php --wp-mock --file=tests/wp-mock/specific-test.php\n");
	echo esc_cli("  php bin/sync-and-test.php --integration --coverage\n");
	echo esc_cli("  php bin/sync-and-test.php --all --verbose\n");
}

// Parse command line arguments
$options = [
	'unit'        => false,
	'wp-mock'     => false,
	'integration' => false,
	'all'         => false,
	'coverage'    => false,
	'verbose'     => false,
	'help'        => false,
	'file'        => '',
];

foreach ($argv as $arg) {
	if (strpos($arg, '--file=') === 0) {
		$options['file'] = substr($arg, 7);
	} elseif ($arg === '--unit') {
		$options['unit'] = true;
	} elseif ($arg === '--wp-mock') {
		$options['wp-mock'] = true;
	} elseif ($arg === '--integration') {
		$options['integration'] = true;
	} elseif ($arg === '--all') {
		$options['all'] = true;
	} elseif ($arg === '--coverage') {
		$options['coverage'] = true;
	} elseif ($arg === '--verbose') {
		$options['verbose'] = true;
	} elseif ($arg === '--help' || $arg === '-h') {
		$options['help'] = true;
	}
}

// Show help if requested or if no test type is specified
if ($options['help'] || (!$options['unit'] && !$options['wp-mock'] && !$options['integration'] && !$options['all'])) {
	print_usage();
	exit(0);
}

// Load settings from .env.testing

$env_file = PROJECT_DIR . '/tests/.env.testing';
colored_message("Loading settings from $env_file", 'blue');
global $loaded_settings;
$loaded_settings = load_settings_file($env_file);

// Define paths from settings

// Get paths from settings
$filesystem_wp_root = get_setting('FILESYSTEM_WP_ROOT');
if (empty($filesystem_wp_root)) {
    colored_message("Error: FILESYSTEM_WP_ROOT setting is not set.", 'red');
    colored_message("Please set this in your .env.testing file or environment.", 'red');
    exit(1);
}

// WP_ROOT is required - must be set in .env.testing
$wp_root = get_setting('WP_ROOT');
if (empty($wp_root)) {
    colored_message("Error: WP_ROOT setting is not set.", 'red');
    colored_message("Please set this in your .env.testing file or environment.", 'red');
    exit(1);
}

$your_plugin_slug = \WP_PHPUnit_Framework\get_setting('YOUR_PLUGIN_SLUG', 'gl-phpunit-testing-framework');
$folder_in_wordpress = \WP_PHPUnit_Framework\get_setting('FOLDER_IN_WORDPRESS', 'wp-content/plugins');

// Set up paths with proper path concatenation
$your_plugin_dest = rtrim($filesystem_wp_root, '/') . '/' . ltrim($folder_in_wordpress, '/') . '/' . $your_plugin_slug;
$test_run_path = rtrim($wp_root, '/') . '/' . ltrim($folder_in_wordpress, '/') . '/' . $your_plugin_slug . '/tests';

colored_message("Using paths:", 'blue');
echo esc_cli("  Project source: " . PROJECT_DIR . "\n");
echo esc_cli("  Filesystem WordPress root: " . $filesystem_wp_root . "\n");
echo esc_cli("  WordPress container root: " . $wp_root . "\n");
echo esc_cli("  Plugin destination: " . $your_plugin_dest . "\n");
echo esc_cli("  Test run path: " . $test_run_path . "\n");

// Check if we're running in a Lando environment
$is_lando = is_lando_environment();

if ($is_lando) {
    colored_message("Running in Lando environment", 'yellow');
}

// Step 0: Update framework files if needed
colored_message("Updating WP PHPUnit Test Framework files...", 'blue');
require_once __DIR__ . '/copy-wp-phpunit-test-framework-convenient-files.php';

// Step 1: Sync plugin to WordPress
colored_message("\nStep 1: Syncing project to WordPress...", 'green');

// Ensure vendor directory exists in source
if (!is_dir(PROJECT_DIR . '/tests/vendor')) {
    colored_message("Installing composer dependencies in source...", 'yellow');
    $tests_dir = PROJECT_DIR . '/tests';
    chdir($tests_dir);
    $composer_cmd = $is_lando ? 'lando composer install' : 'composer install';
    $output = [];
    $return_var = 0;
    exec($composer_cmd . ' 2>&1', $output, $return_var);

    if ($return_var !== 0) {
        colored_message("Error running composer install:", 'red');
        echo esc_cli(implode("\n", $output) . "\n");
        exit(1);
    }
}

// Create destination directory if it doesn't exist
if (!is_dir($your_plugin_dest)) {
	@mkdir($your_plugin_dest, 0755, true);
	if (!is_dir($your_plugin_dest)) {
		colored_message("Warning: Could not create destination directory. This might be a permissions issue.", 'yellow');
		colored_message("If using Lando, you may need to run this command within the Lando environment.", 'yellow');
	}
}

// Call the existing sync-to-wp.php script
$sync_script = SCRIPT_DIR . '/sync-to-wp.php';
if (!file_exists($sync_script)) {
	colored_message("Error: Could not find sync-to-wp.php script at $sync_script", 'red');
	exit(1);
}

// Execute the sync script - always use filesystem PHP
colored_message("Executing sync-to-wp.php...", 'blue');
$sync_cmd = "php $sync_script";
if ($options['verbose']) {
    echo esc_cli("Command: $sync_cmd\n");
}
passthru($sync_cmd, $sync_return);

if ($sync_return !== 0) {
	colored_message("Error: sync-to-wp.php failed with exit code $sync_return", 'red');
	exit($sync_return);
}

// Step 2: Change to the WordPress plugin, tests directory
colored_message("\nStep 2: Changing to WordPress plugin Tests directory...", 'green');
if (!chdir($your_plugin_dest . '/tests')) {
	colored_message("Error: Could not change to WordPress plugin Tests directory: $your_plugin_dest/tests", 'red');
	exit(1);
}
colored_message("Current directory: " . getcwd(), 'blue');

// Step 3: Run the tests
colored_message("\nStep 3: Running tests...", 'green');

/**
 * Build a PHPUnit command with the appropriate options
 *
 * @param string $test_type     The type of test (unit, wp-mock, integration)
 * @param array  $options       Command line options
 * @param string $test_run_path The path where tests will be run from
 * @return string The complete PHPUnit command
 */
function build_phpunit_command($test_type, $options, $test_run_path) {
    global $is_lando, $your_plugin_dest, $wp_root, $folder_in_wordpress, $your_plugin_slug;

    // Determine the PHP command
    $php_command = $is_lando ? 'lando php' : 'php';
    $base_name = 'phpunit-' . $test_type;
    $config_dir = $your_plugin_dest . '/tests/config/';

    // Look for config file in the filesystem
    $config_file = '';
    $possible_files = [
        $config_dir . $base_name . '.xml',
        $config_dir . $base_name . '.xml.dist'
    ];

    // Find the first existing config file
    foreach ($possible_files as $file) {
        if (file_exists($file)) {
            $config_file = $file;
            break;
        }
    }

    if (empty($config_file)) {
        throw new \RuntimeException("Could not find PHPUnit config file. Tried:\n" .
            "- " . implode("\n- ", $possible_files));
    }

    // Get just the filename for the config file
    $config_filename = basename($config_file);

    // Build the path that PHPUnit will use
    $config_path = $is_lando
        ? rtrim($wp_root, '/') . '/' . ltrim($folder_in_wordpress, '/') . '/' . $your_plugin_slug . '/tests/config/' . $config_filename
        : $config_file; // Use full path for local


    // Use the PHPUnit from the test framework's vendor directory
    $phpunit_path = $is_lando
        ? $wp_root . '/' . $folder_in_wordpress . '/' . $your_plugin_slug . '/tests/gl-phpunit-test-framework/vendor/bin/phpunit'
        : __DIR__ . '/../tests/gl-phpunit-test-framework/vendor/bin/phpunit';

    $cmd = $php_command . ' ' . escapeshellarg($phpunit_path) . ' -c ' . escapeshellarg($config_path);

    // Add verbose option if requested
    if ($options['verbose']) {
        $cmd .= ' --verbose';
    }

    // Add test filter if provided
    if (!empty($options['filter'])) {
        $cmd .= ' --filter ' . escapeshellarg($options['filter']);
    }

    return $cmd;
}
// Execute tests based on the selected type
if ($options['unit']) {
    // Run unit tests
    colored_message("Running unit tests...", 'blue');
    $phpunit_cmd = build_phpunit_command('unit', $options, $test_run_path);
    colored_message("Executing: $phpunit_cmd", 'blue');
    passthru($phpunit_cmd, $phpunit_return);
} elseif ($options['wp-mock']) {
	// Run WP_Mock tests
	colored_message("Running WP_Mock tests...", 'blue');
	$phpunit_cmd = build_phpunit_command('wp-mock', $options, $test_run_path);
	colored_message("Executing: $phpunit_cmd", 'blue');
	passthru($phpunit_cmd, $phpunit_return);
} elseif ($options['integration']) {
	// Run integration tests
	colored_message("Running integration tests...", 'blue');
	$phpunit_cmd = build_phpunit_command('integration', $options, $test_run_path);
	colored_message("Executing: $phpunit_cmd", 'blue');
	passthru($phpunit_cmd, $phpunit_return);
} elseif ($options['all']) {
	colored_message("Running all tests sequentially...", 'green');

	// Run unit tests
	colored_message("\nRunning unit tests...", 'blue');
	$unit_cmd = build_phpunit_command('unit', $options, $test_run_path);
	colored_message("Executing: $unit_cmd", 'blue');
	passthru($unit_cmd, $unit_return);

	// Run WP Mock tests
	colored_message("\nRunning WP Mock tests...", 'blue');
	$wp_mock_cmd = build_phpunit_command('wp-mock', $options, $test_run_path);
	colored_message("Executing: $wp_mock_cmd", 'blue');
	passthru($wp_mock_cmd, $wp_mock_return);

	// Run integration tests
	colored_message("\nRunning integration tests...", 'blue');
	$integration_cmd = build_phpunit_command('integration', $options, $test_run_path);
	colored_message("Executing: $integration_cmd", 'blue');
	passthru($integration_cmd, $integration_return);

	// Check if any test suite failed
	if ($unit_return !== 0 || $wp_mock_return !== 0 || $integration_return !== 0) {
		colored_message("\nSome tests failed:", 'red');
		if ($unit_return !== 0) colored_message("  - Unit tests failed with exit code $unit_return", 'red');
		if ($wp_mock_return !== 0) colored_message("  - WP Mock tests failed with exit code $wp_mock_return", 'red');
		if ($integration_return !== 0) colored_message("  - Integration tests failed with exit code $integration_return", 'red');
		exit(1);
	}

	colored_message("\nAll test suites completed successfully! 🎉", 'green');

	// Skip the regular PHPUnit execution since we've already run all test types
	exit(0);
}

// Check if tests passed
if ($phpunit_return === 0) {
	colored_message("\nTests completed successfully! 🎉", 'green');

	// Show coverage report path if generated
	if ($options['coverage']) {
		$coverage_path = $plugin_dest . '/build/coverage/index.html';
		colored_message("Code coverage report is available at:", 'blue');
		colored_message($coverage_path, 'yellow');
		colored_message("You can view this report by opening it in a web browser.", 'blue');
	}
} else {
	colored_message("\nTests failed with exit code $phpunit_return", 'red');
}

exit($phpunit_return);
