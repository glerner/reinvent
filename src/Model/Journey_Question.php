<?php
namespace Reinvent_Coaching_Process\Model;

/**
 * Model for storing and retrieving all phase questions for the Reinvent Coaching Process.
 * Questions are defined as a static PHP array for rapid prototyping; can be migrated to DB later.
 * Each question includes a heading (h2/h3) and text (p).
 */
class Journey_Question {
    /**
     * Get all questions, grouped by phase_type.
     * Each question has: id, phase_type, question_key, question_heading, question_text, is_active
     *
     * @return array
     */
    public static function get_all() {
        return [
            // Phase 1: What Do You Want?
            'what_do_you_want' => [
                [
                    'id' => 1,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'call_to_adventure',
                    'question_heading' => __('The Call to Adventure', 'reinvent-coaching-process'),
                    'question_text' => __('What calls you to reinvent yourself?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'refusal_to_call',
                    'question_heading' => __('The Refusal of the Call', 'reinvent-coaching-process'),
                    'question_text' => __('What doubts, fears, or concerns do you have about embarking on this reinvention journey?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'meeting_the_mentor',
                    'question_heading' => __('Meeting the Mentor', 'reinvent-coaching-process'),
                    'question_text' => __('Who could be a mentor or resource for you in this process?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 4,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'crossing_the_threshold',
                    'question_heading' => __('Crossing the Threshold', 'reinvent-coaching-process'),
                    'question_text' => __('What traits and values do you possess as you step into the unknown?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 5,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'road_of_trials',
                    'question_heading' => __('The Road of Trials', 'reinvent-coaching-process'),
                    'question_text' => __('What activities could you engage in to challenge yourself and grow?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 6,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'ultimate_boon',
                    'question_heading' => __('The Ultimate Boon', 'reinvent-coaching-process'),
                    'question_text' => __('What is the ultimate goal you seek to achieve in your reinvention journey?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 7,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'return_with_elixir',
                    'question_heading' => __('Return with the Elixir', 'reinvent-coaching-process'),
                    'question_text' => __('Looking back, what positive impact has your reinvention had on your life and the lives of others?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 8,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'traits_and_values',
                    'question_heading' => __('Traits and Values', 'reinvent-coaching-process'),
                    'question_text' => __('What traits and values do you possess?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 9,
                    'phase_type' => 'what_do_you_want',
                    'question_key' => 'goals',
                    'question_heading' => __('Goals', 'reinvent-coaching-process'),
                    'question_text' => __('What do you want next in life?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                // Add more as needed...
            ],
            // Phase 2: Past Reinvention
            'past_reinvention' => [
                [
                    'id' => 1,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'gateway',
                    'question_heading' => __('Gateway', 'reinvent-coaching-process'),
                    'question_text' => __('What were the pivotal moments or decisions that led you to embark on this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'experiences',
                    'question_heading' => __('Experiences', 'reinvent-coaching-process'),
                    'question_text' => __('What specific experiences, accomplishments, or challenges did you encounter during this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'friends',
                    'question_heading' => __('Friends', 'reinvent-coaching-process'),
                    'question_text' => __('Who were the key individuals or mentors who supported you during this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 4,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'challenges',
                    'question_heading' => __('Challenges', 'reinvent-coaching-process'),
                    'question_text' => __('What obstacles or setbacks did you face and how did you overcome them?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 5,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'skills_gained',
                    'question_heading' => __('Skills Gained', 'reinvent-coaching-process'),
                    'question_text' => __('What values, skills, strengths, and expertise did you leverage or gain in this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 6,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'insight',
                    'question_heading' => __('Insight', 'reinvent-coaching-process'),
                    'question_text' => __('What valuable insights, lessons, or realizations did you gain from this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 7,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'dilemma',
                    'question_heading' => __('Dilemma', 'reinvent-coaching-process'),
                    'question_text' => __('Were there moments of internal conflict or difficult decisions, and how did you resolve them?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 8,
                    'phase_type' => 'past_reinvention',
                    'question_key' => 'call',
                    'question_heading' => __('Call', 'reinvent-coaching-process'),
                    'question_text' => __('What inspired or motivated you to embark on this reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
            ],
            // Phase 3: Next Possible Reinvention
            'next_possible_reinvention' => [
                [
                    'id' => 1,
                    'phase_type' => 'next_possible_reinvention',
                    'question_key' => 'areas_for_growth',
                    'question_heading' => __('Areas for Growth', 'reinvent-coaching-process'),
                    'question_text' => __('What areas of your life are calling for further growth or reinvention?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 2,
                    'phase_type' => 'next_possible_reinvention',
                    'question_key' => 'skills_and_strengths',
                    'question_heading' => __('Skills and Strengths', 'reinvent-coaching-process'),
                    'question_text' => __('How can you leverage the skills, insights, and strengths gained from past experiences?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 3,
                    'phase_type' => 'next_possible_reinvention',
                    'question_key' => 'recurring_patterns',
                    'question_heading' => __('Recurring Patterns', 'reinvent-coaching-process'),
                    'question_text' => __('Are there recurring patterns or themes in your past reinventions that can guide you?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 4,
                    'phase_type' => 'next_possible_reinvention',
                    'question_key' => 'new_goals',
                    'question_heading' => __('New Goals', 'reinvent-coaching-process'),
                    'question_text' => __('What new goals, challenges, or aspirations do you want to pursue in your next phase?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
                [
                    'id' => 5,
                    'phase_type' => 'next_possible_reinvention',
                    'question_key' => 'alignment_with_self',
                    'question_heading' => __('Alignment with Self', 'reinvent-coaching-process'),
                    'question_text' => __('How can you align your future reinvention journeys with your core values, passions, and vision?', 'reinvent-coaching-process'),
                    'is_active' => true,
                ],
            ],
        ];
    }
}
