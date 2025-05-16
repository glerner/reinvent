<?php
/**
 * Plugin Name: Reinvent Coaching Process
 * Description: Minimal plugin for Reinvent Coaching Process. Adds admin menu and config panel.
 * Version: 0.1.0
 * Author: George Lerner
 * License: GPL2+
 * Text Domain: reinvent-coaching-process
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Reinvent_Coaching_Process_Plugin {
    public function __construct() {
        add_action('admin_menu', [$this, 'register_admin_menu']);
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
        echo '<h1>' . esc_html__('Reinvent Coaching Process Settings', 'reinvent-coaching-process') . '</h1>';
        echo '<p>' . esc_html__('This is the configuration panel for the Reinvent Coaching Process plugin. It is working!', 'reinvent-coaching-process') . '</p>';
        echo '</div>';
    }
}

// Initialize the plugin
new Reinvent_Coaching_Process_Plugin();
