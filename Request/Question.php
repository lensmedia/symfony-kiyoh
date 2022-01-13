<?php

namespace Lens\Bundle\KiyohBundle\Request;

class Question
{
    public string $group;
    public string $type;

    public string $question;
    public bool|int|string $answer;

    public $order;

    public function __construct(array $response)
    {
        $this->group = strtolower($response['questionGroup']);
        $this->type = strtolower($response['questionType']);
        $this->question = $response['questionTranslation'];

        switch ($this->type) {
            case 'int':
                $this->answer = (int) $response['rating'];
                break;

            case 'boolean':
                $this->answer = (bool) $response['rating'];
                break;

            default:
                $answer = $response['rating'];

                $length = mb_strlen($answer);
                $first = mb_substr($answer, 0, 1);
                $rest = mb_substr($answer, 1, $length - 1);

                $this->answer = mb_strtoupper($first).$rest;

                break;
        }

        $this->order = $response['order'];
    }
}
