<?php
/**
 * Journey Answer Form View (phase_type: what_do_you_want)
 *
 * Displays a form for answering journey questions (phase: what_do_you_want) and shows submitted answers below.
 * No database or REST integration yet.
 *
 * @package GL_Reinvent\View
 */

namespace GL_Reinvent\View;


/**
 * Render the journey answer form and display submitted answers.
 *
 * @return void
 */
function journey_answer_form_view($plugin) {
    $phase = $plugin->get_phase_description('what_do_you_want');
    $questions = $plugin->get_questions('what_do_you_want');

    // Handle form submission
    $answers = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reinvent_journey_answers'])) {
        foreach ($questions as $q) {
            $key = $q['question_key'];
            $answers[$key] = isset($_POST['reinvent_journey_answers'][$key]) ? trim($_POST['reinvent_journey_answers'][$key]) : '';
        }
    }
    ?>
    <h2><?= esc_html($phase['heading']) ?></h2>
    <p><?= esc_html($phase['description']) ?></p>
    <form method="post">
        <?php foreach ($questions as $q): ?>
            <h2><?php echo esc_html($q['question_heading']); ?></h2>
            <p><?php echo $q['question_text']; ?></p>
            <textarea
                name="reinvent_journey_answers[<?php echo esc_attr($q['question_key']); ?>]"
                rows="8"
                style="width:100%; resize:vertical; overflow:auto;"
            ><?php echo isset($answers[$q['question_key']]) ? esc_textarea($answers[$q['question_key']]) : ''; ?></textarea>
        <?php endforeach; ?>
        <p><button type="submit">Submit</button></p>
    </form>
    <?php if (!empty($phase['closing'])): ?>
        <p><?php echo $phase['closing']; ?></p>
    <?php endif; ?>
    <?php
    // After submit, show answers below the form
    if (!empty($answers)) {
        reinvent_journey_detail_view($questions, $answers);
    }
}

/**
 * Display submitted journey answers below the form.
 *
 * @param array $questions The list of questions.
 * @param array $answers The submitted answers keyed by question_key.
 * @return void
 */
function reinvent_journey_detail_view($questions, $answers) {
    foreach ($questions as $q) {
        $key = $q['question_key'];
        echo '<h2>' . esc_html($q['question_heading']) . '</h2>';
        echo '<p>' . (!empty($answers[$key]) ? nl2br(esc_html($answers[$key])) : '<em>No answer provided.</em>') . '</p>';
    }
}
