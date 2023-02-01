<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

class Review
{
    public Uuid $id;

    public string $author;

    public string $city;

    public float $rating;

    public array $questions;

    public DateTimeInterface $createdAt;

    public DateTimeInterface $updatedAt;

    public string $language;

    public function __construct(array $response)
    {
        $this->id = Uuid::fromString($response['reviewId']);
        $this->author = $response['reviewAuthor'];
        $this->city = $response['city'];
        $this->rating = (float)$response['rating'];

        $this->questions = array_map(
            static fn($question) => new Question($question),
            $response['reviewContent'],
        );

        usort(
            $this->questions,
            static fn($a, $b) => $a->order <=> $b->order,
        );

        $this->createdAt = new DateTimeImmutable($response['dateSince']);
        $this->updatedAt = new DateTimeImmutable($response['updatedSince']);
        $this->language = strtolower($response['reviewLanguage']);
    }
}
