# Code Inventory: Reinvent Coaching Process Plugin (WordPress Naming Conventions)

## Namespace & Coding Conventions

- All PHP code for the plugin uses the `GL_Reinvent` namespace (PSR-4 compliant; matches `src/` directory structure).
- **Global Classes:** Global PHP and WordPress classes (e.g., `\Exception`, `\WP_Post`) must be referenced with a leading backslash.
- **Namespace References:** Use the "fully qualified from current namespace" approach for clarity, e.g.:
  ```php
  // If you're in this namespace:
  namespace GL_Reinvent\Service;
  // Reference a child namespace like this:
  use Model\User_Profile;
  // Refers to Reinvent_Coaching_Process\Service\Model\User_Profile
  ```
- Directory structure and namespaces must match (PSR-4).

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

## Data Models / Structures

Note: PK is Primary Key for a database table. FK is Foreign Key, for looking up information in another table.

### User_Profile
- `id` (PK, int): Unique identifier for the person being guided (referenced by all phase tables as `person_user_id`)
- `person_name` (string)
- `created_at`, `updated_at` (datetime)
- [Future fields: personality_type, skills, etc.]

### Journey
- `id` (PK, int)
- `coach_user_id` (int, FK to WP user)
- `person_user_id` (int, FK to User_Profile.id)
- `title` (string)
- `created_at`, `updated_at` (datetime)

### Journey_Question
- `id` (PK, int)
- `phase_type` (string)
- `question_key` (string)
- `question_heading` (string) - H2/H3 tag
- `question_text` (string) - P tag
- `is_active` (boolean)
- `created_at`, `updated_at` (datetime)

### Journey_Step
- `id` (PK, int)
- `journey_id` (int, FK to Journey.id)
- `question_id` (int, FK to Journey_Question.id)
- `content` (text/JSON)
- `created_at` (datetime)

**Note:** Questions are stored in a static PHP array for easy access and modification.

**Relationships:**
- `person_user_id` in Journey references `User_Profile.id`
- `journey_id` in Journey_Step references `Journey.id`
- `question_id` in Journey_Step references `Journey_Question.id`

## Core Classes / Services

## Data Models / Structures

- **Journey**: Represents a reinvention journey for a specific person, guided by the logged-in user (the coach/facilitator)
  - `id` (int, PK)
  - `coach_user_id` (int) — WordPress user ID of the facilitator/coach (the logged-in user)
  - `person_user_id` (string|int|null) — unique identifier for the person being guided, not a WordPress user ID.
  - `title` (string)
  - `created_at` (datetime)
  - `updated_at` (datetime)

- **Journey_Step**: Represents a step in the Hero's Journey/Super Self process for a specific journey
  - `id` (int, PK)
  - `journey_id` (int, FK to Journey)
  - `question_id` (int, FK to Journey_Question)
  - `content` (text/JSON)
  - `created_at` (datetime)

- **User_Profile**: Rich profile for the person being guided (AI-generated, user-editable)
  - `id` (int, PK)
  - `person_name` (string) — Name of the person being guided
  - `person_user_id` (string|int|null)
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
  - `created_at` (datetime)
  - `updated_at` (datetime)

### Custom Post Type
- **reinvent_journey** — Stores each journey as a custom post type. Each post represents a reinvention process for a specific person. Post meta stores person_name, person_user_id, and other metadata.

### Custom DB Tables
- uses wp-config.php table prefix, represented here by 'wp_'
- **wp_reinvent_journeys**
  - id (PK), coach_user_id (FK), person_name, person_user_id, title, created_at, updated_at
- **wp_reinvent_journey_steps**
  - id (PK), journey_id (FK), step_type, content, created_at
- **wp_reinvent_user_profiles**
  - id (PK), person_name, person_user_id, ... (see User_Profile fields above), created_at, updated_at

## Core Classes / Services

### Reinvent_Journey_Service
- `create_journey( $coach_user_id, $person_name, $person_user_id, $title )`
  - Create a new reinvention journey for a given person, guided by the logged-in coach.
- `get_journeys_by_coach( $coach_user_id )`
  - Retrieve all journeys created by a specific coach (logged-in user).
- `get_journeys_for_person( $coach_user_id, $person_user_id )`
  - Retrieve all journeys for a specific person, as guided by the coach.
- `get_journey( $journey_id )`
  - Get a single journey by ID.
- `update_journey( $journey_id, $data )`
  - Update journey details.
- `delete_journey( $journey_id )`
  - Delete a journey.

**Class Signature:**
```php
class Reinvent_Journey_Service {
    public function create_journey( $coach_user_id, $person_name, $person_user_id, $title );
    public function get_journeys_by_coach( $coach_user_id );
    public function get_journeys_for_person( $coach_user_id, $person_user_id );
    public function get_journey( $journey_id );
    public function update_journey( $journey_id, $data );
    public function delete_journey( $journey_id );
}
```

  **Example Function with PHPDoc:**
  ```php
  /**
   * Create a new reinvention journey for a person, guided by a coach.
   *
   * @param int $coach_user_id WordPress user ID of the coach/facilitator
   * @param string $person_name Name of the person being guided
   * @param int|string|null $person_user_id Unique ID for the person being guided (optional)
   * @param string $title Title of the journey
   * @return int|WP_Error Journey ID on success, WP_Error on failure
   */
  public function create_journey( $coach_user_id, $person_name, $person_user_id, $title ) {
      // Implementation here...
  }
  ```

### Journey_Step_Service
- `add_step( $journey_id, $step_type, $content )`
  - Add a step to a journey.
- `get_steps( $journey_id )`
  - Get all steps for a journey.
- `update_step( $step_id, $content )`
  - Update a step's content.
- `delete_step( $step_id )`
  - Delete a step.

**Class Signature:**
```php
class Journey_Step_Service {
    public function add_step( $journey_id, $step_type, $content );
    public function get_steps( $journey_id );
    public function update_step( $step_id, $content );
    public function delete_step( $step_id );
}
```

### Personality_Analysis_Service (future)
- `analyze_profile( $person_user_id )`
  - Analyze a person's profile (AI-powered, future feature).
- `suggest_reinventions( $journey_id )`
  - Suggest reinventions for a journey (future feature).

**Class Signature:**
```php
class Personality_Analysis_Service {
    public function analyze_profile( $person_user_id );
    public function suggest_reinventions( $journey_id );
}
```

**Multi-person Guidance Logic:**
- All methods in Reinvent_Journey_Service and related controllers/services take `$coach_user_id` (the logged-in user) and `$person_user_id` (the person being guided) as parameters, ensuring one coach can guide multiple people, each with their own journeys and steps.

## Controllers

### Reinvent_Journey_Controller
Handles user requests for creating, viewing, editing journeys.

**Class Signature:**
```php
class Reinvent_Journey_Controller {
    public function list_journeys( $coach_user_id ); // List all journeys for the coach
    public function view_journey( $coach_user_id, $journey_id ); // View a single journey
    public function create_journey( $coach_user_id, $person_name, $person_user_id, $title ); // Create a new journey
    public function update_journey( $coach_user_id, $journey_id, $data ); // Update a journey
    public function delete_journey( $coach_user_id, $journey_id ); // Delete a journey
}
```

### Journey_Step_Controller
Handles adding/editing steps within a journey.

**Class Signature:**
```php
class Journey_Step_Controller {
    public function list_steps( $coach_user_id, $journey_id ); // List all steps for a journey
    public function view_step( $coach_user_id, $step_id ); // View a single step
    public function add_step( $coach_user_id, $journey_id, $step_type, $content ); // Add a new step
    public function update_step( $coach_user_id, $step_id, $content ); // Update a step
    public function delete_step( $coach_user_id, $step_id ); // Delete a step
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

### reinvent_journey_detail_view
Shows all steps, questions, and answers for a specific journey and person.
- **Data Source:**
  - Receives data from `Reinvent_Journey_Controller::view_journey()` and `Journey_Step_Controller::list_steps()` (PHP) or via REST API (`GET /wp-json/reinvent/v1/journeys/{id}`)
- **Interaction:**
  - Fetches journey and associated steps for display
  - May allow navigation between steps
- **Display:**
  - Uses custom template `templates/journey-detail.php` or block render callback
  - Uses WordPress functions for escaping and formatting output
- **Extensibility:**
  - Action hooks before/after journey and step output (e.g., `do_action('reinvent_before_journey_detail')`)
- **Assets:**
  - Enqueues CSS for layout, and JS for navigation or step expansion

### journey_step_form_view
Presents a dynamic form for entering or editing answers for each process step/question.
- **Data Source:**
  - Receives data from `Journey_Step_Controller::view_step()` (PHP) or REST API (`GET /wp-json/reinvent/v1/steps/{id}`)
- **Interaction:**
  - Submits data to controller or REST endpoint for saving (`POST /wp-json/reinvent/v1/steps` or AJAX)
  - Calls validation helper before saving
- **Display:**
  - Uses custom template `templates/step-form.php` or block render callback
  - Uses WordPress form functions (`wp_nonce_field`, `wp_create_nonce`, etc.)
- **Extensibility:**
  - Filters for modifying form fields (`reinvent_step_form_fields`)
  - Action hooks on save (`do_action('reinvent_step_saved')`)
- **Assets:**
  - Enqueues JS for dynamic fields, validation, and autosave; CSS for form styling

### analysis_view (future)
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

## Utilities / Helpers
- **Questionnaire_Helper**: Loads and manages the reflective questions and explanations (from config, DB, or file)
- **Validation_Helper**: Validates user input for each step

## WordPress Plugin Infrastructure & Integration

### Plugin Registration & Initialization
- Registers the plugin with WordPress (`reinvent-coaching-process.php` main file)
- Loads text domain for translations
- Registers activation/deactivation hooks
- Initializes core services, controllers, and custom post types/tables
- Hooks: `plugins_loaded`, `init`, `register_activation_hook`, `register_deactivation_hook`

### Settings & Options
- Adds plugin settings/options page in the WordPress admin
- Stores settings in the options table (e.g., default behaviors, export options, AI integration keys)
- Hooks: `admin_menu`, `admin_init`, `add_options_page`
- File: `src/Admin/Settings_Page.php`

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

### Other WordPress Integrations
- Loads plugin text domain for translation
- Follows WordPress coding standards and security best practices
- Supports multisite/network activation
- Adds uninstall script for clean removal
- File: `uninstall.php`, `languages/`, `src/`

## Testing
- **Unit tests** for all services and controllers (PHPUnit)
