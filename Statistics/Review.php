<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

use DateTimeImmutable;

class Review
{
    public $id;
    public $author;
    public $city;
    public $rating;
    public $questions;
    public $createdAt;
    public $updatedAt;

    public function __construct(array $response)
    {
        $this->id = $response['reviewId'];
        $this->author = $response['reviewAuthor'];
        $this->city = $response['city'];
        $this->rating = $response['rating'];

        $this->questions = array_map(function ($question) {
            return new Question($question);
        }, $response['reviewContent']);

        usort($this->questions, function ($a, $b) {
            return $a->order > $b->order;
        });

        $this->createdAt = new DateTimeImmutable($response['dateSince']);
        $this->updatedAt = new DateTimeImmutable($response['updatedSince']);
    }
}
