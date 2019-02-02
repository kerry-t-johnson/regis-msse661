<?php
require_once 'test_data.php';

function get_content($in) {
    global $content;

    foreach($content as $content_id => $c) {
        if($content_id == $in) {
            // Generate fake but realistic looking content:
            $c['markup'] = file_get_contents('http://loripsum.net/api/' . $c['path']);

            return $c;
        }
    }

    return false;
}

function get_answers($question_id) {
    global $answers;

    $ret = [];
    foreach($answers as $answer_id => $answer) {
        if($answer['question_id'] == $question_id) {
            $ret[$answer_id] = $answer;
        }
    }

    return $ret;
}

function get_user($in) {
    global $users;

    foreach($users as $user_id => $user) {
        if($user_id == $in) {
            return $user;
        }
    }

    return false;
}