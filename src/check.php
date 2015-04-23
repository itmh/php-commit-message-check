<?php

define('ERR_SUBJECT_REQUIRED', 'Сообщение должно содержать заголовок, отделённый пустой строкой от содержимого');
define('ERR_SUBJECT_TOO_LONG', 'Заголовок не должен превышать 50 символов');
define('ERR_SUBJECT_REDUNDANT_DOT', 'Заголовок не должен заканчиваться точкой');
define('ERR_SUBJECT_WRONG_CASE', 'Заголовок должен начинаться с большой буквы');

/**
 * @param $text string Текст сообщения
 * @return array Список ошибок
 */
function check($text)
{
    $internal_encoding = mb_internal_encoding();
    mb_internal_encoding('utf-8');

    $text = trim(preg_replace('/^#.*$/m', '', $text));

    $errors = [];
    $data = explode("\n\n", $text, 2);

    if (count($data) !== 2) {
        $errors[ERR_SUBJECT_REQUIRED] = 1;
    }

    $subject = $data[0];
    if (mb_strlen($subject) > 50) {
        var_dump($subject, mb_strlen($subject));
        $errors[ERR_SUBJECT_TOO_LONG] = 1;
    }

    if (mb_substr($subject, -1) === '.') {
        $errors[ERR_SUBJECT_REDUNDANT_DOT] = 1;
    }

    $subject_first_letter = mb_substr($subject, 0, 1);
    if ($subject_first_letter !== mb_convert_case($subject_first_letter, MB_CASE_UPPER)) {
        $errors[ERR_SUBJECT_WRONG_CASE] = 1;
    }

    mb_internal_encoding($internal_encoding);

    return $errors;
}
