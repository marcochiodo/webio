<?php

namespace Helper;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RetryMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpRetry {

    function __construct(
        public int $max_retries
    ) {
    }

    function getDeciderCallback(): callable {
        $max_retries = $this->max_retries;
        return function (
            int $retries,
            RequestInterface $Request,
            ?ResponseInterface $Response = null,
            ?GuzzleException $Exception = null
        ) use ($max_retries): bool {

            $status_code = $Response ? $Response->getStatusCode() : null;

            return ($retries < $max_retries) &&
                (!$status_code ||
                    ($status_code >= 500 && $status_code <= 599) ||
                    $status_code == 429
                ) &&
                !$Exception instanceof \GuzzleHttp\Exception\TooManyRedirectsException;
        };
    }

    function getDelayCallback(): callable {
        return function (
            int $retries,
            ResponseInterface $Response
        ): int {

            if (!$Response->hasHeader('Retry-After')) {
                return RetryMiddleware::exponentialDelay($retries);
            }

            $retryAfter = $Response->getHeaderLine('Retry-After');

            if (!is_numeric($retryAfter)) {
                $retryAfter = (new \DateTime($retryAfter))->getTimestamp() - time();
            }

            return (int) $retryAfter * 1000;
        };
    }
}
