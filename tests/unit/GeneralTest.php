<?php

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testPassedWhenGoodMessage()
    {
        $message = <<<MSG
Это образцово показательный коммит

Он содержит заголовк, отделённый пустой строкой
и расширенное содержимое.
MSG;
        $check = commit_message_check($message);
        $this->assertTrue(count($check) === 0);
    }

    public function testFailedWhenNoSubject()
    {
        $message = <<<MSG
А в этом сообщении тема не отделена
пустой строкой, поэтому тест провалится
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_SUBJECT_REQUIRED, $check);
    }

    public function testFailedWhenSubjectTooLong()
    {
        $message = <<<MSG
Следует ограничивать длину заголовка пятьюдесятью| символами, иначе неудобно читать будет

Краткость сестра таланта, ёпта
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_SUBJECT_TOO_LONG, $check);
    }

    public function testFailedWhenSubjectWithDot()
    {
        $message = <<<MSG
А в этом сообщении в конце темы стоит точка.

Хотя она там совершенно не нужна
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_SUBJECT_REDUNDANT_DOT, $check);
    }

    public function testFailedWhenSubjectWrongCase()
    {
        $message = <<<MSG
тема должна начинаться с большой буквы

Это для лучшей читаемости
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_SUBJECT_WRONG_CASE, $check);
    }

    public function testFailedWhenMessageWrongCase()
    {
        $message = <<<MSG
Содержимое тоже должно начинаться с большой буквы

иначе не очень красиво получается
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_MESSAGE_WRONG_CASE, $check);
    }

    public function testFailedWhenMessageIsJustLineBreak()
    {
        $message = <<<MSG
Некоторые думают наделать много пустых строк



MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_SUBJECT_REQUIRED, $check);
    }

    public function testFailedWhenMessageWithoutDot()
    {
        $message = <<<MSG
Содержимое должно заканчиваться точкой

Всё-таки законченное предложение, иначе белиберда какая-то
MSG;
        $check = commit_message_check($message);
        $this->assertArrayHasKey(ERR_MESSAGE_WITHOUT_DOT, $check);
    }
}
