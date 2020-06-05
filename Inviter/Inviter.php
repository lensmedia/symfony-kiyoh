<?php

namespace Lens\Bundle\KiyohBundle\Inviter;

use DOMDocument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Inviter
{
    const TYPE_XML = 'xml';
    const TYPE_JSON = 'json';
    const TYPE_URL = 'url';

    private $http;
    private $logger;
    private $options;

    public function __construct(
        HttpClientInterface $http,
        LoggerInterface $logger,
        array $options
    ) {
        $this->http = $http;
        $this->logger = $logger;
        $this->options = $options;
    }

    public function invite(string $email, string $name = null, string $reference = null, string $locale = null): RequestContent
    {
        $options = new RequestContent(
            array_merge($this->options, ['language' => $locale]),
            $email,
            $name,
            $reference
        );

        return $this->request($options);
    }

    private function request(RequestContent $requestContent)
    {
        $type = $this->options['invites']['request_type'];

        $requestOptions = [];
        $method = 'GET';
        switch ($type) {
            case self::TYPE_XML:
            case self::TYPE_JSON:
                $method = 'POST';

                $requestOptions['headers'] = [
                    'X-Publication-Api-Token' => $this->options['invites']['api_key'],
                    'content-type' => 'application/'.$type,
                ];

                $requestOptions['body'] = $this->encode($requestContent);
                break;

            case self::TYPE_URL:
                $requestOptions['query'] = (array) $requestContent;
        }

        $response = $this->http->request($method, $this->getTargetUrl(), $requestOptions);

        try {
            $content = json_decode($response->getContent(), true);

            return $requestContent;
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $status = $response->getStatusCode(false);
            $content = $response->toArray(false);

            throw new InviteRequestException(
                $status,
                '['.$content['detailedError'][0]['errorCode'].'] '.$content['detailedError'][0]['message']
            );
        }
    }

    private function encode(RequestContent $requestOptions)
    {
        $options = (array) $requestOptions;

        switch ($this->options['invites']['request_type']) {
            case self::TYPE_XML:
                $document = new DOMDocument();
                $root = $document->createElement('invitation');

                array_walk($options, function ($value, $key) use ($document, $root) {
                    if (null === $value) {
                        return;
                    }

                    $element = $document->createElement($key, $value);
                    $root->appendChild($element);
                });

                $document->appendChild($root);

                return $document->saveXML();

            case self::TYPE_JSON:
                return json_encode($options, JSON_UNESCAPED_SLASHES);
        }
    }

    private function getTargetUrl()
    {
        return $this->options['invites']['base_url'].'/v1/invite/external';
    }
}
