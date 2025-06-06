# Reinvent Coaching Process – WordPress Plugin

A professional WordPress plugin for guiding users through the Reinvent Coaching Process, inspired by the Hero's Journey and Super Self frameworks. The plugin presents a series of reflective questions, explanations, and input fields to help users document, edit, and analyze their personal reinvention journeys.

The app will ask the user about themselves:

- ask the user what they want next in life

- ask the user what skills they have, what projects interest them, what their attention is on, what they are drawn to, what they want to learn

- ask the users about past times they reinvented themselves, or had a massive change in life. Go through each of the coaching questions, for each element of that reinvention

- analyze the user's personality type, motivators, what they avoid, what learning style they have,

- analyze the progression in their life, how each reinvention got them to the start of the next reinvention, leading them to where they are today

- Taking everything the user has said, select 5 to 20 ways they could reinvent themselves now, consistent with who they are now, that would increase their financial, business, health, relationship, community, as many aspects of their life as possible.

## Features

- Interactive questionnaire with explanations for each step of the reinvention process

- Supports multiple reinvention journeys per user

- Save, retrieve, and edit answers at any time

- Clean, accessible UI for large text input fields

- Integration-ready for future AI-powered analysis and insights

- Built using PHP, WordPress API, and follows Model-Service-View-Controller (MSVC) architecture

- Fully unit tested with PHPUnit and custom test framework

## Getting Started

### Prerequisites
- WordPress 6.0+
- PHP 8.0+
- Composer (for dependency management)
- Git

### Installation
1. Clone this repository into your WordPress `wp-content/plugins/` directory:
   ```bash
   cd ~/your-sites-wordpress/wp-content/plugins
   git clone https://github.com/glerner/reinvent.git reinvent-coaching-process
   cd reinvent-coaching-process
   ```

2. Initialize and update submodules:
   ```bash
   git submodule update --init --recursive
   ```

3. Install Composer dependencies:
   ```bash
   composer install
   ```

## Development Setup

### Composer Configuration
The plugin uses Composer for autoloading and dependency management. The root `composer.json` defines the autoloading for the `GL_Reinvent` namespace.

### Testing Framework
The testing framework is included as a Git submodule. The `sync-to-wp.php` script handles merging the test framework's Composer configuration with the plugin's configuration.

### Running Tests

1. Ensure you have a local WordPress installation with a test database
2. Copy `.env.testing.example` to `.env.testing` and update the database credentials
3. Run the sync and test script:
   ```bash
   php bin/sync-and-test.php --unit
   ```

Available test commands:
- `--unit`: Run unit tests
- `--wp-mock`: Run WP_Mock tests
- `--integration`: Run integration tests
- `--all`: Run all test suites

### Development Workflow
1. Make your code changes
2. Write or update tests
3. Run the tests:
   ```bash
   php bin/sync-and-test.php --unit
   ```
4. The script will automatically sync your changes to the test environment and run the tests
5. Activate the plugin from the WordPress admin dashboard.

## Development
- Follows WordPress coding standards and naming conventions
- Uses Model-Service-View-Controller (MSVC) structure
- All classes, methods, and variables are documented in `docs/guides/code-inventory.md`
- Unit tests are written using PHPUnit (see `tests/` directory)

## Documentation
- See `docs/guides/developer-guide.md` for setup, architecture, and contribution guidelines.
- See `docs/guides/code-inventory.md` for a full inventory of classes, methods, and variables.

## Contributing
Contributions are welcome! Please:
- Follow the coding standards and architecture described in the developer guide
- Write or update unit tests for any code changes
- Document new classes or methods in the code inventory

## License
[MIT License](LICENSE)
