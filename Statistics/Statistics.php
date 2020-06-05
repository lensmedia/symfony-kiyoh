<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

class Statistics
{
    public $rating;
    public $votes;
    public $recommended;

    public $locationId;
    public $locationName;

    public function __construct(array $response)
    {
        $this->rating = (float) $response['averageRating'];
        $this->votes = (int) $response['numberReviews'];
        $this->recommended = (float) $response['percentageRecommendation'] / 100;

        $this->locationId = (int) $response['locationId'];
        $this->locationName = $response['locationName'];
    }
}
