# Code Inventory: Reinvent Coaching Process Plugin

## Development Workflow

### Sync-Test-Commit-Document Process
1. **Sync**: Run `bin/sync-and-test.php` to synchronize code to the WordPress environment
2. **Test**: Execute all tests (my WP_PHPUnit_Framework, installed in tests/gl-phpunit-test-framework, source in ~/sites/phpunit-testing/) and verify PHPStan compliance;
3. **Commit**: Commit code changes with a descriptive message
4. **Document**: Update documentation if needed, then commit documentation changes separately

### Environment Management
- Development code lives in `~/sites/reinvent/`
- WordPress environment is in Lando `~/sites/wordpress/`, this plugin copied to ~/sites/wordpress/wp-content/plugins/gl-reinvent/
- Use `bin/sync-to-wp.php` for manual syncing
- Environment variables are managed via `.env.testing` (copied to WordPress plugin directory)

## WordPress Naming Conventions
- **Files:** lowercase-with-hyphens (e.g. `wp-config.php`)
- **Classes:** PascalCase (e.g., `Journey_Answer_Service`)
- **Functions/Methods:** snake_case (e.g., `add_answer`)
- **Parameters:** snake_case
- **WordPress Hooks/Filters/Template Functions:** snake_case (e.g., `reinvent_journey_list_view`)

Use these conventions throughout the codebase for clarity and consistency.

## Namespace & Coding Conventions

- All PHP code for the plugin uses the `GL_Reinvent` namespace (PSR-4 compliant with WordPress naming conventions; matches `src/` directory structure).
- **Global Classes:** Global PHP and WordPress classes (e.g., `\Exception`, `\WP_Post`) must be referenced with a leading backslash.
- **Namespace References:** Use the "fully qualified from current namespace" approach for clarity, e.g.:
  ```php
  // If you're in this namespace:
  namespace GL_Reinvent\Service;
  // Reference a child namespace like this:
  use Model\Person_Profile;
  // Refers to Reinvent_Coaching_Process\Service\Model\Person_Profile
  ```
- Directory structure and namespaces must match (PSR-4).

## Modern PSR-4 + WordPress Standards

### Directory Structure
```
reinvent/
├── src/                    # All PHP classes (PSR-4 autoloaded)
│   ├── Model/            # Domain models
│   ├── Service/          # Business logic
│   └── Controller/       # Request handlers
├── tests/                # Test files (WordPress style)
│   ├── Unit/            # Unit tests
│   ├── Integration/      # Integration tests
│   └── WP-Mock/         # WP-Mock tests
├── assets/              # CSS, JS, images (kebab-case)
├── templates/          # Template files (kebab-case)
├── composer.json       # PSR-4 autoloading config
└── reinvent.php        # Main plugin file (kebab-case)
```

### Naming Conventions

| Type | Location | Naming Convention | Example |
|------|----------|-------------------|---------|
| **Class Files** | `src/` | Match class name (PascalCase) | `Journey_Questions_Model.php` |
| **Test Files** | `tests/` | `test-{feature}.php` (kebab-case) | `test-journey-questions.php` |
| **Main Plugin File** | Root | `plugin-name.php` (kebab-case) | `reinvent.php` |
| **Assets** | `assets/` | kebab-case | `main.js`, `admin-styles.css` |
| **Templates** | `templates/` | kebab-case | `single-journey.php` |

### Autoloading Configuration
```json
{
    "autoload": {
        "psr-4": {
            "GL_Reinvent\\": "src/"
        }
    }
}
```

### Key Points
1. **Class Files**:
   - Must match class name exactly (case-sensitive)
   - Example: `class Journey_Questions_Model` → `src/Model/Journey_Questions_Model.php`

2. **Test Files**:
   - Use `test-` prefix
   - Describe feature being tested
   - Example: `tests/Unit/test-journey-questions.php`

3. **Non-PHP Files**:
   - Always use kebab-case
   - Examples: `admin-styles.css`, `main.js`, `single-journey.php`

## Data Models / Structures

Table names will use wp-config.php table prefix + 'reinvent_', not mentioned here

### Person_Profile
Rich profile for the person being guided (User entered, ai_person_summary AI-generated, all user-editable)

Table: wp_reinvent_person_profiles
- `id` (PK, int): Unique identifier for the person being guided (referenced by all other tables as `person_id`)
- `person_name` (string) — Name of the person being guided
- `email_address` (string) - send PDF/ODT/Markdown of entire results (future enhancement, need email opt-in)
- `personality_type` (string)
- `motivators` (text)
- `avoidances` (text)
- `learning_style` (string)
- `interests` (text)
- `accomplishments` (text)
- `skills` (text)
- `talents` (text)
- `current_learning` (text)
- `external_feedback` (text) — What others say they are good at
- `goals` (text) — What they want to achieve
- `drawn_to` (text)
- `wants_to_learn` (text)
- `passions` (text)
- `ai_person_summary` (JSON) # AI-generated summary of the profile
- `coach_id` (int, FK to WP user) # WordPress user id using this plugin
- `notes` (text)
- `last_modified` (datetime)

*Relationships:*
- `person_id` in Journey references `Person_Profile.id`

### Journey

Represents a reinvention journey for a specific person. Each journey (major change of their life) would have a set of answers to questions (journey answers).

Table: wp_reinvent_journeys
- `id` (int, PK)
- `person_id` (int, FK to Person_Profile.id) — unique identifier for the person being guided
- `title` (string) Name of this journey, a time of major change
- `notes` (text)
- `last_modified` (datetime)

*Relationships:*
- `journey_id` in Journey_Answer references `Journey.id`

### Phase Descriptions Structure
Phase descriptions provide context and guidance for each phase of the reinvention journey. These are stored in a static PHP array in `Journey_Questions_Model`.

- **Location:** `GL_Reinvent\Model\Journey_Questions_Model`, `$descriptions` array
- **Structure:**
  - `heading` (string): Short heading text for the phase.
  - `description` (string): Long, descriptive text for the phase. May include HTML for formatting.
  - `closing` (string): Optional closing/summary text for the phase. May include HTML for formatting.
- **Allowed HTML tags:** `<p>`, `<br>`, `<i>`, `<b>`, `<ul>`, `<li>`, `<code>`, `<img>`, flexbox-related markup, WordPress video embedding.

### Journey Questions Structure
Questions are stored in a static PHP array for easy access and modification.

Table: wp_reinvent_journey_questions or array: $reinvent_journey_questions
- `id` (PK, int)
- `phase_type` (string)
  - what_do_you_want | What do you want?
  - what_reinventions_already | What ways have you Reinvented yourself already?
  - your_values_strengths | Your values and strengths?
  - done_this_before | You have done this before?
  - next_reinvention | What might be your next reinvention?
- `question_key` (string): Internal descriptive key for the question
- `question_heading` (string): Short heading (H2/H3)
- `question_text` (string): Long text describing the question. May include HTML for formatting. **Allowed HTML tags:** `<p>`, `<br>`, `<i>`, `<b>`, `<ul>`, `<li>`, `<code>`, `<img>`, flexbox-related markup, WordPress video embedding.
- `notes` (text, for user notes; store in database but no current plugin use for it)

*Relationships:*
- `question_id` in Journey_Answer references `Journey_Question.id`

### Journey_Answer
Represents a step in the Hero's Journey/Super-Self process for a specific journey

Table: wp_reinvent_journey_answers
- `id` (int, PK)
- `person_id` (int, FK to Person_Profile.id)
- `journey_id` (int, FK to Journey.id)
- `question_id` (int, FK to Journey_Question.id)
- `answer` (text/JSON) # user-entered, might contain Markdown, or basic formatting available to a Paragraph block
- `notes` (text) # for User to put whatever they want, including reminders to themselves
- `last_modified` (datetime)

*Note:* One row per question/answer is recommended for querying/filtering.

## Core Classes / Services

### Reinvent_Journey_Service

**Name:** Reinvent_Journey_Service

**Location:** `/src/Service/Reinvent_Journey_Service.php`

**Purpose:** Manage journeys for a specific person, guided by a coach (WordPress user, often self-coaching).

Every person has already had several major life changes. Each of these is a journey.

Supports creation, retrieval, update, and deletion of journeys.

**Signatures:**
```php
class Reinvent_Journey_Service {
    /**
     * List all people's journeys for the current coach (shorthand for list_journeys(['coach_id' => current_user_id])).
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on DB error
     */
    public function list_journeys_for_coach();

    /**
     * List all journeys for the current coach (shorthand for list_journeys(['coach_id' => current_user_id])).
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on DB error
     */
    public function list_journeys_by_coach();

    /**
     * List all journeys for a specific person (shorthand for list_journeys(['person_id' => $person_id])).
     * @param int $person_id
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function list_journeys_for_person($person_id);

    /**
     * List journeys using arbitrary filters.
     * @param array $filters
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function list_journeys($filters = []);

    /**
     * Get a single journey by ID.
     * @param int $journey_id
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on DB error or if not found
     */
    public function get_journey($journey_id);

    /**
     * Add a new journey.
     * @param int $person_id
     * @param string $title Name of this journey, a time of major change
     * @return int Journey ID on success
     * @throws \Exception on DB error or invalid input
     */
    public function add_journey($person_id, $title);

    /**
     * Update a journey.
     * @param int $journey_id
     * @param array $answers
     * @return bool
     * @throws \Exception on DB error or invalid input
     */
    public function update_journey($journey_id, $answers);

    /**
     * Delete a journey.
     * @param int $journey_id
     * @return bool
     * @throws \Exception on DB error or if not found
     */
    public function delete_journey($journey_id);
}
```

### Journey_Answer_Service

**Name:** Journey_Answer_Service

**Location:** `/src/Service/Journey_Answer_Service.php`

**Purpose:** Add, retrieve, update, and delete answers to questions for a given journey.

**Signature:**
```php
class Journey_Answer_Service {
    /**
     * Add answers to a journey.
     * @param int $journey_id
     * @param array $answers
     * @return bool True on success
     * @throws \Exception on DB error or invalid input
     */
    public function add_answers($journey_id, array $answers);

    /**
     * Get all answers for a journey.
     * @param int $journey_id
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on DB error or if not found
     */
    public function get_answers($journey_id);

    /**
     * Update answers for a journey.
     * @param int $journey_id
     * @param array $answers
     * @return bool True on success
     * @throws \Exception on DB error or invalid input
     */
    public function update_answers($journey_id, array $answers);

    /**
     * Delete all answers for a journey.
     * @param int $journey_id
     * @return bool True on success
     * @throws \Exception on DB error or if not found
     */
    public function delete_answers($journey_id);
}
```

### Personality_Analysis_Service

```php
/**
 * Service for analyzing a person's profile using AI.
 * Will query AI specified in Settings.
 */
class Personality_Analysis_Service {
    /**
     * Analyze a person's profile and return insights.
     * @param int $person_id
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on analysis error or invalid input
     */
    public function analyze_profile($person_id);

    /**
     * Suggest reinventions for a journey (future feature).
     * @param int $journey_id
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on analysis error, DB error, or invalid input
     */
    public function suggest_reinventions($journey_id);
}
```

## Controllers

### Reinvent_Journey_Controller
Handles user requests for creating, viewing, editing journeys.

**Class Signature:**
```php
class Reinvent_Journey_Controller {
    /**
     * List all journeys for a specific person (shorthand for list_journeys(['person_id' => $person_id])).
     * @param int $person_id
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function list_journeys_for_person($person_id);

    /**
     * List journeys using arbitrary filters.
     * @param array $filters
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function list_journeys($filters = []);

    /**
     * Get a single journey by ID (for internal use).
     * @param int $journey_id
     * @return array[] Array of associative arrays, each containing long text fields.
     * @throws \Exception on DB error or if not found
     */
    public function get_journey($journey_id);

    /**
     * Render the journey detail view.
     * @param int $journey_id
     * @return void
     * @throws \Exception on DB error or if not found
     */
    public function view_journey($journey_id);

    /**
     * Add a new journey.
     * @param int $person_id
     * @param string $title
     * @return int
     * @throws \Exception on DB error
     */
    public function add_journey($person_id, $title);

    /**
     * Update a journey.
     * @param int $journey_id
     * @param array $answers
     * @return bool
     * @throws \Exception on DB error
     */
    public function update_journey($journey_id, $answers);

    /**
     * Delete a journey.
     * @param int $journey_id
     * @return bool
     * @throws \Exception on DB error
     */
    public function delete_journey($journey_id);
}
```

### Journey_Answer_Controller
Handles adding/editing answers within a journey.

**Class Signature:**
```php
class Journey_Answer_Controller {
    /**
     * List all answers for a journey.
     * @param int $journey_id
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function list_answers($journey_id);

    /**
     * View a single answer.
     * @param int $answer_id
     * @return array[] Array of associative arrays, each containing long text fields.
     */
    public function view_answer($answer_id);

    /**
     * Add a new answer.
     * @param int $journey_id
     * @param string $answer_type
     * @param string $content
     * @return bool
     * @throws \Exception on DB error or invalid input
     */
    public function add_answer($journey_id, $answer_type, $content);

    /**
     * Update an answer.
     * @param int $answer_id
     * @param string $content
     * @return bool
     * @throws \Exception on DB error or invalid input
     */
    public function update_answer($answer_id, $content);

    /**
     * Delete an answer.
     * @param int $answer_id
     * @return bool
     * @throws \Exception on DB error or if not found
     */
    public function delete_answer($answer_id);
}
```

## Views / UI Components

### reinvent_journey_list_view
Displays a list of all reinvention journeys for the logged-in coach/facilitator.
- **Data Source:**
  - Receives data from `Reinvent_Journey_Controller::list_journeys()` (PHP) or via REST API endpoint (`GET /wp-json/reinvent/v1/journeys`)
- **Interaction:**
  - Calls controller/service to fetch journeys for the current user
  - May use AJAX or server-rendered template
- **Display:**
  - Uses custom template in `templates/journey-list.php` or a registered block render callback
  - Can be rendered as a Gutenberg block or shortcode
  - Uses WordPress classes (e.g., `WP_List_Table` for admin, or custom markup for frontend)
- **Extensibility:**
  - Provides filters for modifying the journey query and output (e.g., `reinvent_journey_list_query`, `reinvent_journey_list_item_html`)
- **Assets:**
  - Enqueues CSS/JS for filtering, searching, or pagination if needed

```php
/**
 * Render the list view of journeys.
 * @param array[] $journeys List of journeys to display
 * @return void
 */
function reinvent_journey_list_view($journeys);
```

### reinvent_journey_detail_view
Shows all answers, questions, and answers for a specific journey and person.
- **Data Source:**
  - Receives data from `Reinvent_Journey_Controller::view_journey()` and `Journey_Answer_Controller::list_answers()` (PHP) or via REST API (`GET /wp-json/reinvent/v1/journeys/{id}`)
- **Interaction:**
  - Fetches journey and associated answers for display
  - May allow navigation between answers
- **Display:**
  - Uses custom template `templates/journey-detail.php` or block render callback
  - Uses WordPress functions for escaping and formatting output
- **Extensibility:**
  - Action hooks before/after journey and answer output (e.g., `do_action('reinvent_before_journey_detail')`)
- **Assets:**
  - Enqueues CSS for layout, and JS for navigation or answer expansion

```php
/**
 * Render the detail view for a single journey.
 * @param array[] $journey Journey data
 * @return void
 */
function reinvent_journey_detail_view($journey);
```

### journey_answer_form_view
Presents a dynamic form for entering or editing answers for each process answer/question.
- **Data Source:**
  - Receives data from `Journey_Answer_Controller::view_answer()` (PHP) or REST API (`GET /wp-json/reinvent/v1/answers/{id}`)
- **Interaction:**
  - Submits data to controller or REST endpoint for saving (`POST /wp-json/reinvent/v1/answers` or AJAX)
  - Submits data to controller or REST endpoint for saving (`POST /wp-json/reinvent/v1/steps` or AJAX)
  - Calls validation helper before saving
- **Display:**
  - Uses custom template `templates/answer-form.php` or block render callback
  - Uses WordPress form functions (`wp_nonce_field`, `wp_create_nonce`, etc.)
- **Extensibility:**
  - Filters for modifying form fields (`reinvent_step_form_fields`)
  - Action hooks on save (`do_action('reinvent_step_saved')`)
- **Assets:**
  - Enqueues JS for dynamic fields, validation, and autosave; CSS for form styling

```php
/**
 * Render the form for editing/adding answers to a journey.
 * @param array[] $journey Journey data
 * @param array[] $answers List of answers
 * @return void
 */
function journey_answer_form_view($journey, $answers);
```

### analysis_view
Displays AI-powered insights and recommendations based on the journey and profile data.
- **Data Source:**
  - Receives data from `Personality_Analysis_Service` (PHP) or REST API endpoint
- **Interaction:**
  - Fetches analysis results for the journey or profile
  - May call AI service asynchronously and display results via AJAX
- **Display:**
  - Uses custom template or block render callback for insights
  - May display charts, summaries, or recommendations
- **Extensibility:**
  - Filters for modifying analysis results output (`reinvent_analysis_output`)
- **Assets:**
  - JS for interactive charts or AI feedback; CSS for highlighting insights

```php
/**
 * Render the analysis view for a person's profile.
 * @param array[] $analysis Analysis results
 * @return void
 */
function analysis_view($analysis);
```

## Utilities / Helpers
- Utility/helper functions should follow camelCase for PHP and snake_case for WordPress integration.

```php
/**
 * Utility to sanitize journey data.
 * @param array[] $data Array of associative arrays, each containing long text fields.
 * @return array[] Sanitized data
 */
function sanitize_journey_data($data);
```

## WordPress Plugin Infrastructure & Integration

### Plugin Registration & Initialization

```php
/**
 * Register and initialize the plugin.
 * @return void
 * @throws \Exception on plugin load failure
 */
function reinvent_plugin_init();
```

### Settings & Options

```php
/**
 * Register plugin settings and options.
 * @return void
 */
function reinvent_register_settings();
```

### Block Registration
- Registers one or more custom Gutenberg blocks for embedding the coaching UI in posts/pages
- Registers block assets (JS/CSS)
- Hooks: `init`, `enqueue_block_editor_assets`, `enqueue_block_assets`
- File: `src/Blocks/`

### Admin Panels
- Adds custom admin panels for managing journeys, people, and exports
- May add submenu pages under the plugin or in the user profile
- Hooks: `admin_menu`, `add_submenu_page`
- File: `src/Admin/`

### Export to PDF/ODT
- Provides export functionality for journeys to PDF and ODT (LibreOffice Write)
- Option to export single or multiple journeys
- Uses libraries such as TCPDF, Dompdf, or PHPWord for PDF/ODT generation
- Adds export buttons in the UI and/or admin
- File: `src/Export/Export_Service.php`

### REST API Endpoints
- Registers custom REST API endpoints for AJAX and block interactivity
- Endpoints for CRUD operations on journeys, steps, and profiles
- Hooks: `rest_api_init`
- File: `src/Api/`

### Hooks & Filters
- Provides custom hooks and filters for extensibility (e.g., `reinvent_journey_saved`, `reinvent_profile_updated`)
- Allows other plugins/themes to extend or modify plugin behavior

## Exception Handling

- A global exception handler (using `set_exception_handler`) is registered in plugin bootstrap.
- All uncaught exceptions (`\Throwable`) are caught and displayed in a user-friendly format (with color in CLI, or styled HTML in admin/frontend).
- Handler provides message, file, line, and stack trace; exits with error code 1.
- Example:
  ```php
  set_exception_handler(function(\Throwable $e) {
      // Display formatted error
      exit(1);
  });
  ```

### Permissions

- **user_id** (int, FK to WordPress user): Reserved for future use in role/permission management. For now, WordPress Author permissions are sufficient for all plugin actions.

### Other WordPress Integrations
- Loads plugin text domain for translation
- Follows WordPress coding standards and security best practices
- Supports multisite/network activation
- Adds uninstall script for clean removal
- File: `uninstall.php`, `languages/`, `src/`

## Testing

See the [GL PHPUnit Testing Framework documentation](tests/gl-phpunit-test-framework/docs/guides/code-inventory.md) for complete details on:

- Test types and structure
- Namespace and naming conventions
- Test class and method patterns
- Setup and execution instructions

Test Files:

- Unit Tests: `tests/unit`
- WP Mock Tests: `tests/wp-mock`
- Integration Tests: `tests/integration`

To run tests:
```bash
# Sync and run all tests
php bin/sync-and-test.php --all

# Run specific test types
php bin/sync-and-test.php --unit
php bin/sync-and-test.php --wp-mock
php bin/sync-and-test.php --integration
```
