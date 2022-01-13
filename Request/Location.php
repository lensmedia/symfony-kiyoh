<?php

namespace Lens\Bundle\KiyohBundle\Request;

use DateTimeImmutable;

class Location
{
    /** @var string The account id for which the hashtoken is requested */
    public string $locationId;

    /** @var int The unique Klantenvertellen ID of the specific location */
    public int $uniqueId;

    /** @var string The name of the category */
    public string $categoryName;

    /** @var string The location street */
    public string $street;

    /** @var string The location house number */
    public string $houseNumber;

    /** @var string The location house number extension */
    public string $houseNumberExtension;

    /** @var string The location postal code */
    public string $postcode;

    /** @var string The location city */
    public string $city;

    /** @var string The location country */
    public string $country;

    /** @var float The average rating for this location */
    public float $averageRating;

    /** @var int The total number of reviews for this location */
    public int $numberReviews;

    /** @var float percentage of people recommend this location */
    public float $percentageRecommendation;

    /** @var array<int> The number of each 5 star rating count for this location eg; 8 voted 1, 24 voted 2 etc */
    public array $starRating;

    /** @var string URL where rating reviews can be retrieved */
    public string $viewReviewUrl;

    /** @var string URL where a review can be placed */
    public string $createReviewUrl;

    /** @var string SEO friendly name of the specific location */
    public string $canonicalName;

    /** @var string Name of the specific location */
    public string $locationName;

    /** @var DateTimeImmutable Date and time since latest review. For example: 2017-09-12T16:28:14.295+02:00 */
    public DateTimeImmutable $updatedSince;

    /** @var DateTimeImmutable Date and time since creation of specific location. For example: 2018-03-07T12:14:05.096+01:00 */
    public DateTimeImmutable $dateSince;

    /** @var string Website address of the specific location */
    public string $website;

    /** @var string Email address of the specific location */
    public string $email;

    public string $productId;
    public string $crmId;
    public bool $locationActive;
    public string $categoryId;
    public int $numberOfUsedInvites;
    public string $externalId;

    public function __construct(array $response)
    {
        $this->locationId = $response['locationId'];
        $this->uniqueId = $response['uniqueId'];
        $this->categoryName = $response['categoryName'];
        $this->street = $response['street'];
        $this->houseNumber = $response['houseNumber'];
        $this->houseNumberExtension = $response['houseNumberExtension'];
        $this->postcode = $response['postcode'];
        $this->city = $response['city'];
        $this->country = $response['country'];
        $this->averageRating = $response['averageRating'];
        $this->numberReviews = $response['numberReviews'];
        $this->percentageRecommendation = $response['percentageRecommendation'] / 100;
        $this->starRating = [
            $response['oneStars'],
            $response['twoStars'],
            $response['threeStars'],
            $response['fourStars'],
            $response['fiveStars'],
        ];
        $this->viewReviewUrl = $response['viewReviewUrl'];
        $this->createReviewUrl = $response['createReviewUrl'];
        $this->canonicalName = $response['canonicalName'];
        $this->locationName = $response['locationName'];
        $this->dateSince = $response['dateSince'];
        $this->updatedSince = $response['updatedSince'];
        $this->website = $response['website'];

        // Some random shit related to the subscription things
        // that are not even documented but are sent along.
        $this->email = $response['email'];
        $this->productId = $response['productId'];
        $this->crmId = $response['crmId'];
        $this->locationActive = $response['locationActive'];
        $this->categoryId = $response['categoryId'];
        $this->numberOfUsedInvites = $response['numberOfUsedInvites'];
        $this->externalId = $response['externalId'];
    }
}
