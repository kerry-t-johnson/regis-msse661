<?php
/**
 * Created by PhpStorm.
 * User: kerry
 * Date: 1/25/2019
 * Time: 5:33 PM
 */

require_once 'multidimensional-associative-array.php';

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testGetAnswers(): void
    {
        $expected = [
            'question-id-1' => 2,
            'question-id-20' => 3,
            'question-id-333' => 1,
            'question-id-4' => 0,
        ];

        foreach ($expected as $question_id => $num_answers) {
            $answers = get_answers($question_id);

            $this->assertEquals($num_answers, count($answers));
        }
    }

    public function testGetUser(): void
    {
        $expected = [
            'user-id-1' => 'Flintstone',
            'user-id-2' => 'Rubble',
            'user-id-3' => false,
        ];

        foreach ($expected as $user_id => $user_last_name) {
            $user = get_user($user_id);

            $this->assertEquals($user_last_name, $user !== false ? $user['last_name'] : false);
        }
    }
}
