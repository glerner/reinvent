<?php
/**
 * Unit tests for Journey_Questions_Model
 *
 * @package GL_Reinvent\Tests\Unit
 */

declare(strict_types=1);

namespace GL_Reinvent\Tests\Unit;

use GL_Reinvent\Model\Journey_Questions_Model;
use Mockery;
use WP_PHPUnit_Framework\Unit\Unit_Test_Case;

/**
 * Test case for the Journey_Questions_Model class
 *
 * @covers \GL_Reinvent\Model\Journey_Questions_Model
 */
class Test_Journey_Questions_Model extends Unit_Test_Case {
    /**
     * Test instance
     *
     * @var Journey_Questions_Model
     */
    private $instance;

    /**
     * Set up the test environment
     */
    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->instance = new Journey_Questions_Model();
    }

    /**
     * Tear down the test
     *
     * @return void
     */
    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test get_phase_description with valid phase types
     *
     * @dataProvider data_valid_phase_types
     */
    public function test_get_phase_description_returns_expected_structure(string $phase_type): void {
        $result = $this->instance->get_phase_description($phase_type);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('heading', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('closing', $result);
        $this->assertStringContainsString('phase_heading', $result['heading']);
    }

    /**
     * Data provider for valid phase types
     */
    public static function data_valid_phase_types(): array {
        return [
            ['what_do_you_want'],
            ['heros_journey'],
            ['past_reinventions'],
            ['mapping_reinvention'],
            ['next_reinvention']
        ];
    }

    /**
     * Test get_phase_description with invalid phase type
     */
    public function test_get_phase_description_handles_invalid_phase_type(): void {
        $result = $this->instance->get_phase_description('invalid_phase_type');

        $this->assertIsArray($result);
        $this->assertEquals([
            'heading' => '',
            'description' => '',
            'closing' => ''
        ], $result);
    }

    /**
     * Test get_phase_description with empty phase type
     */
    public function test_get_phase_description_handles_empty_phase_type(): void {
        $result = $this->instance->get_phase_description('');

        $this->assertIsArray($result);
        $this->assertEquals([
            'heading' => '',
            'description' => '',
            'closing' => ''
        ], $result);
    }

    /**
     * Test get_questions returns expected structure
     */
    public function test_get_questions_returns_expected_structure(): void {
        $questions = $this->instance->get_questions();

        $this->assertIsArray($questions);
        $this->assertNotEmpty($questions);

        $first_question = $questions[0];
        $this->assertArrayHasKey('phase_type', $first_question);
        $this->assertArrayHasKey('question_key', $first_question);
        $this->assertArrayHasKey('question_heading', $first_question);
        $this->assertArrayHasKey('question_text', $first_question);

        $this->assertStringContainsString('question_text', $first_question['question_text']);
    }

    /**
     * Test get_questions contains all expected phase types
     */
    public function test_get_questions_contains_expected_phase_types(): void {
        $questions = $this->instance->get_questions();
        $phase_types = array_unique(array_column($questions, 'phase_type'));

        $expected_phase_types = [
            'what_do_you_want',
            'heros_journey',
            'past_reinventions',
            'mapping_reinvention',
            'next_reinvention'
        ];

        foreach ($expected_phase_types as $expected_type) {
            $this->assertContains($expected_type, $phase_types);
        }
    }

    /**
     * Test all question keys are unique
     */
    public function test_get_questions_has_unique_question_keys(): void {
        $questions = $this->instance->get_questions();
        $question_keys = array_column($questions, 'question_key');
        $unique_keys = array_unique($question_keys);

        $this->assertCount(
            count($question_keys),
            $unique_keys,
            'All question keys should be unique'
        );
    }
}
