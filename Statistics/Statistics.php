<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

class Statistics
{
    public float $rating;
    public int $votes;
    public float $recommended;

    public int $locationId;
    public string $locationName;

    public function __construct(array $response)
    {
        $this->rating = (float) $response['averageRating'];
        $this->votes = (int) $response['numberReviews'];
        $this->recommended = (float) $response['percentageRecommendation'] / 100;

        $this->locationId = (int) $response['locationId'];
        $this->locationName = $response['locationName'];
    }
}
