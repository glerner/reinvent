<?php
namespace GL_Reinvent\Model;

class Journey_Questions_Model {
    /**
     * Returns an associative array of phase descriptions keyed by phase_type.
     */
    public function get_phase_description($phase_type) {
        $descriptions = [
            'what_do_you_want' => [
                'heading' => '<h2 class="phase_heading">What Do You Want?</h2>',
                'description' => <<<HTML
<div class="phase_description"><p>Identify the areas of your life where you feel the need to reinvent or improve. This could include your professional goals, desires, personal growth, social relationships, health, and financial stability.</p></div>
HTML,
                'closing' => '',
            ],
            'heros_journey' => [
                'heading' => '<h2 class="phase_heading">Begin Looking at Your Life as Several Hero\'s Journeys</h2>',
                'description' => <<<HTML
<div class="phase_description"><p>To guide you through the reinvent process using the Hero's Journey and Super Self ideas, we can break down your journey of reinvention into several stages inspired by the Hero's Journey framework. Don't try now to get every major change in your life mapped onto each of the stages of the Hero's Journey. Begin to see how each stage might apply to something you've already gone through, and awaken your memories of those important changes in your life.</p></div>
HTML,
                'closing' => <<<HTML
<div class="phase_closing"><p>By integrating the Super Self ideas of self-improvement, personal growth, and alignment with your core values, you can navigate through the Hero's Journey of reinvention and emerge as a stronger, more fulfilled version of yourself.<br>Remember to stay true to your values, embrace change with an open mind, and trust in your ability to overcome challenges and achieve your goals.<br>We will be exploring all of this in more detail.<br>Good luck on your reinvention journey!</p></div>
HTML,
            ],
            'past_reinventions' => [
                'heading' => '<h2 class="phase_heading">You Have Done This Before</h2>',
                'description' => <<<HTML
<div class="phase_description"><p>You have likely embarked on several reinvention journeys in your life, each aligning with the Hero's Journey framework and 'Your Personal Myth & Your Superstory' ideas.<br>Review your life, especially key moments where you have reinvented yourself:</p></div>
HTML,
                'closing' => <<<HTML
<div class="phase_closing"><p>By reflecting on these past reinvention journeys and recognizing the growth and transformation you have experienced, you can draw inspiration and insights to guide you through your current reinvention process.<br>Embrace your past successes, learn from challenges, and leverage your strengths and values to shape your future Superstory and continue evolving into the best version of yourself.</p></div>
HTML,
            ],
            'mapping_reinvention' => [
                'heading' => '<h2 class="phase_heading">Mapping each Reinvention Experience</h2>',
                'description' => <<<HTML
<div class="phase_description"><p>For today, pick 3-5 of the Reinvention times of your life. (If you want, you can fill in more another time.) Then you will separately map each of them into: Gateway, Experiences, Friends, Challenges, Skills, Insight, Dilemma, Call that illustrate that one Hero's Journey. It is helpful to name each Reinvention, for clarity while reviewing it. By reflecting on your past reinvention experiences, identifying key elements of the Hero's Journey in each journey, and recognizing how they have shaped your personal growth and development, you can gain a deeper understanding of your personal growth journey and identify patterns that may guide you towards your next possible Hero's Journeys.</p></div>
HTML,
                'closing' => '',
            ],
            'next_reinvention' => [
                'heading' => '<h2 class="phase_heading">What Might Your Next Reinvention Journey Be?</h2>',
                'description' => <<<HTML
<div class="phase_description"><p>After mapping out my past reinvention experiences, I can explore potential future reinventions by asking myself the following questions:</p></div>
HTML,
                'closing' => <<<HTML
<div class="phase_closing"><p>By reflecting on my past reinventions, identifying patterns, and exploring new possibilities for growth and transformation, I can continue to evolve and shape my personal myth and Superstory in alignment with my values and aspirations.</p></div>
HTML,
            ],
        ];
        return $descriptions[$phase_type] ?? [
            'heading' => '',
            'description' => '',
            'closing' => '',
        ];
    }

    public function get_questions() {
        $questions = [
            // What Do You Want?
            [
                'phase_type' => 'what_do_you_want',
                'question_key' => 'what_is_life_like_now',
                'question_heading' => 'What is Life Like Now?',
                'question_text' => <<<HTML
<div class="question_text"><p>Identify the areas of your life where you feel the need to reinvent or improve. This could include your professional goals, desires, personal growth, social relationships, health, and financial stability.</p><p><i>List several ways</i>, what calls you to reinvent yourself?</p></div>
HTML,
            ],
            // Hero's Journey Phases
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'call_to_adventure',
                'question_heading' => 'The Call to Adventure',
                'question_text' => <<<HTML
<div class="question_text"><p>Identify areas of your life where you felt the need to reinvent or improve; or some new opportunity was tempting you or thrust itself on you. This could include your professional goals, desires, personal growth, social relationships, health, and financial stability, and even more.</p><p><i>List several ways</i>, what called you to reinvent yourself?</p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'refusal_of_the_call',
                'question_heading' => 'The Refusal of the Call',
                'question_text' => <<<HTML
<div class="question_text"><p>Acknowledge any self-doubt or concerns you probably had about embarking on this reinvention journey. For example, your concerns about failing or struggling. What were those voices in your head saying? Some version of "better to stay who you are"?</p>
<p><i>Write several doubts, fears, concerns</i> you had, about embarking on these reinvention journeys</p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'meeting_the_mentor',
                'question_heading' => 'Meeting the Mentor',
                'question_text' => <<<HTML
<div class="question_text"><p>How did you seek out mentors or resources to guide you through those reinvention processes? These could have included meetings with a health coach, or a business expert who could guide you through expanding your business or finances.</p>
<p><i>List several mentors, resources, coaches, teachers, friends</i> that you went to for guidance</p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'crossing_the_threshold',
                'question_heading' => 'Crossing the Threshold',
                'question_text' => <<<HTML
<div class="question_text"><p>What traits and values did you bring with you as you stepped into the unknown territory of reinventing yourself?<br>This could have involved, as examples:<br>- flexible approaches to solving problems<br>- willingness to correct from mistakes<br>- spontaneity<br>- risk-tolerance<br>These are very likely still with you today.</p><p><i>List several Traits and Values that you possess</i></p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'road_of_trials',
                'question_heading' => 'The Road of Trials',
                'question_text' => <<<HTML
<div class="question_text"><p>What were activities that you engaged in, that challenged you to grow and evolve? How were these in line with your goals and aspirations? What was important about them? How were they "hard but worth it"?</p>
<p><i>List several activities you engaged in</i></p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'ultimate_boon',
                'question_heading' => 'The Ultimate Boon',
                'question_text' => <<<HTML
<div class="question_text"><p>As you progressed on your several reinvention journeys, what was the ultimate goal you sought to achieve, the life you wanted to have?</p>
<p><i>List several ultimate goals</i> you sought to achieve, the life you wanted to have.</p></div>
HTML,
            ],
            [
                'phase_type' => 'heros_journey',
                'question_key' => 'return_with_the_elixir',
                'question_heading' => 'The Return with the Elixir',
                'question_text' => <<<HTML
<div class="question_text"><p>Once you achieved your reinvention goals, look back from where you are now, and reflect on the positive impact each of these new Reinventions had on your life and the lives of others.<br>As examples, this could include:<br>- enjoying collaborative relationships<br>- feeling less alone<br>- achieving financial and personal fulfillment</p>
<p><i>List several positive impacts</i> you had on your life, through these Hero's Journeys.</p></div>
HTML,
            ],
            // You Have Done This Before (Past Reinventions)
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'professional_educational',
                'question_heading' => 'Professional and Educational Reinvention',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself professionally and educationally.</p></div>',
            ],
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'personal_growth',
                'question_heading' => 'Personal Growth and Skill Development',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself personally and skill development.</p></div>',
            ],
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'emotional_identity',
                'question_heading' => 'Emotional and Identity Transformation',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself emotionally and transformed your identity.</p></div>',
            ],
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'health_wellness',
                'question_heading' => 'Health and Wellness Reinvention',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself in health and wellness.</p></div>',
            ],
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'financial_economic',
                'question_heading' => 'Financial and Economic Reinvention',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself financially and economically.</p></div>',
            ],
            [
                'phase_type' => 'past_reinventions',
                'question_key' => 'social_relational',
                'question_heading' => 'Social and Relational Reinvention',
                'question_text' => '<div class="question_text"><p><i>List several ways</i> you reinvented yourself socially and relationally.</p></div>',
            ],
            // Mapping Each Reinvention Experience (repeat for each reinvention)
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'gateway',
                'question_heading' => 'Gateway',
                'question_text' => <<<HTML
<div class="question_text"><p><i>What were the pivotal moments or decisions that led you to embark on a journey of reinvention in one or several areas of your life (professional, personal, health, financial, social)?</i></p>
<p>- What was the starting point of your journey?</p>
<p>- Where was the doorway you went through that put you in a new land?</p>
<p>- What was the first place you went, physically, emotionally, or mentally that had you experience the beginning of this new world?</p>
</div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'experiences',
                'question_heading' => 'Experiences',
                'question_text' => <<<HTML
<div class="question_text"><p><i>What specific experiences, accomplishments, or challenges did you encounter during each reinvention process?</i><br><i>How did these experiences shape your personal growth and development?</i></p>
<p>- What are your formative memories?</p>
<p>- What made a strong imprint on you that lasted?</p>
<p>- What were your experiential lessons?</p>
<p>- What represents the joy of curiosity and discovery?</p>
<p>- What moments represent the pain of instruction?</p>
</div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'friends',
                'question_heading' => 'Friends',
                'question_text' => <<<HTML
<div class="question_text"><p>- Who were your friends and helpers?</p>
<p>- Who were your allies and supporters?</p>
<p>- Who was on your team?</p>
<p>- Who were your teachers and mentors?</p>
<p>- Who showed you the way and guided you?</p>
<p>- Who will you always remember?</p>
<p><i>What was it about them that was so valuable and helpful?</i></p>
<p><i>How did their guidance and encouragement influence your path of transformation?</i></p>
</div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'challenges',
                'question_heading' => 'Challenges',
                'question_text' => <<<HTML
<div class="question_text"><p>- What were the obstacles you faced?</p>
<p>- What were your trials and tribulations?</p>
<p>- What was the darkest period of your experience at that level?</p>
<p>- Who were the enemies, bad guys, evil people, stupid ones, criminals, insane people?</p>
<p><i>How did you overcome these challenges and what did you learn from them?</i></p></div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'skills',
                'question_heading' => 'Skills',
                'question_text' => <<<HTML
<div class="question_text"><p>- What key values, skills, strengths, and expertise did you learn in this stage?</p>
<p>- What has become a lens or prism that you now use to look through to understand the world?</p>
<p>- What are the mental models of how things work that you took away to use in the rest of your life?</p></div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'insight',
                'question_heading' => 'Insight',
                'question_text' => <<<HTML
<div class="question_text"><p>- What was your breakthrough realization about how the world works?</p>
<p>- What was your hard-fought and hard-won lesson that you will take away from that stage of your life?</p>
<p>- What is the wisdom that you now own forever?</p>
<p>- Where can you teach and guide others, now that you’ve been there?</p></div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'dilemma',
                'question_heading' => 'Dilemma',
                'question_text' => <<<HTML
<div class="question_text"><p>- What was the transformational dilemma you encountered when you got to the end of your journey?</p>
<p>- What was the anomaly, the contradiction, the paradox you faced?</p>
<p>- What was the taboo trade-off that you had to confront?</p>
<p>- What choice did you make that changed you forever?</p>
<p>How did you resolve these dilemmas and stay true to your values and goals?</p></div>
HTML,
            ],
            [
                'phase_type' => 'mapping_reinvention',
                'question_key' => 'call',
                'question_heading' => 'Call',
                'question_text' => <<<HTML
<div class="question_text"><p>- What called you to a higher possibility in your life?</p>
<p>- What acted as an attractor that pulled you over the threshold and up the staircase to look at what was possible at the next level?</p>
<p>- What was the visit you made to your future?</p>
<p>- What represented the new motive and motivator that captivated you?</p></div>
HTML,
            ],
            // Next Reinvention Journey
            [
                'phase_type' => 'next_reinvention',
                'question_key' => 'areas_for_growth',
                'question_heading' => 'What areas of your life are calling for further growth, development, or reinvention at this stage?',
                'question_text' => '<div class="question_text"><p><i>List as richly as possible, all the areas of your life that you feel the desire to reinvent or improve.</i></p></div>',
            ],
            [
                'phase_type' => 'next_reinvention',
                'question_key' => 'leverage_skills',
                'question_heading' => 'How can you leverage the skills, insights, and strengths gained from past experiences to fuel your next reinvention journey?',
                'question_text' => '<div class="question_text"><p><i>List as skillfully as possible, all the skills, insights, and strengths you have gained from past experiences.</i></p></div>',
            ],
            [
                'phase_type' => 'next_reinvention',
                'question_key' => 'recurring_patterns',
                'question_heading' => 'Are there recurring patterns or themes in your past reinventions that can guide you towards new opportunities for personal and professional growth?',
                'question_text' => '<div class="question_text"><p><i>List as vividly as possible, all the recurring patterns or themes in your past reinventions.</i></p></div>',
            ],
            [
                'phase_type' => 'next_reinvention',
                'question_key' => 'new_goals',
                'question_heading' => 'What new goals, challenges, or aspirations do you want to pursue in your next phase of self-discovery and transformation?',
                'question_text' => '<div class="question_text"><p><i>List as imaginatively as possible, all the new goals, challenges, or aspirations you want to pursue in your next phase of self-discovery and transformation.</i></p></div>',
            ],
            [
                'phase_type' => 'next_reinvention',
                'question_key' => 'align_values',
                'question_heading' => 'How can you align your future reinvention journeys with your core values, passions, and vision for a fulfilling and purposeful life?',
                'question_text' => '<div class="question_text"><p><i>As wisely as possible, list all the core values, passions, and vision for a fulfilling and purposeful life you want to align your future reinvention journeys with.</i></p></div>',
            ],
        ];
        return $questions;
    }
}
