<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $responseBody;

    protected function seeJsonEquals(array $data): self
    {
        $actual = json_encode(Arr::sortRecursive(
            $this->getResponseAsArray()
        ));

        PHPUnit::assertEquals(json_encode(Arr::sortRecursive($data)), $actual);

        return $this;
    }

    protected function getResponseAsArray()
    {
        return json_decode($this->responseBody, true);
    }
}
