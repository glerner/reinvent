<?php
/**
 * Plugin Name: Reinvent Coaching Process
 * Description: Plugin for Reinvent Coaching Process. Admin menu, config panel, coaching questions
 * Version: 0.1.1
 * Author: George Lerner
 * License: GPL2+
 * Text Domain: reinvent-coaching-process
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Reinvent_Coaching_Process_Plugin {
    private $questions_model;

    public function __construct() {
        require_once __DIR__ . '/src/Model/Journey_Questions_Model.php';
        $this->questions_model = new \GL_Reinvent\Model\Journey_Questions_Model();
        add_action('admin_menu', [$this, 'register_admin_menu']);
    }

    public function get_questions($phase_type = null) {
        $questions = $this->questions_model->get_questions();
        if ($phase_type === null) {
            return $questions;
        }
        return array_filter($questions, function($q) use ($phase_type) {
            return $q['phase_type'] === $phase_type;
        });
    }

    public function get_phase_description($phase_type) {
        return $this->questions_model->get_phase_description($phase_type);
    }

    /**
     * Register the admin menu entry.
     */
    public function register_admin_menu(): void {
        add_menu_page(
            __('Reinvent Coaching', 'reinvent-coaching-process'),
            __('Reinvent Coaching', 'reinvent-coaching-process'),
            'manage_options',
            'reinvent-coaching-process',
            [$this, 'render_admin_page'],
            'dashicons-lightbulb',
            26
        );
    }

    /**
     * Render the admin config panel.
     */
    public function render_admin_page(): void {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Reinvent Coaching Process', 'reinvent-coaching-process') . '</h1>';
        // Require the journey answer form view file
        require_once plugin_dir_path(__FILE__) . 'src/View/Journey_Answer_Form_View.php';
        if (function_exists('GL_Reinvent\\View\\journey_answer_form_view')) {
            \GL_Reinvent\View\journey_answer_form_view($this);
        } else {
            echo '<p style="color:red;">Form view function not found.</p>';
        }
        echo '</div>';
    }
}

// Initialize the plugin
new Reinvent_Coaching_Process_Plugin();
