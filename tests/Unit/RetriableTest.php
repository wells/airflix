<?php

namespace Tests\Unit;

use Airflix\Retriable;
use Tests\TestCase;

class RetriesImpl {
    use Retriable;
}

class RetriableTest extends TestCase
{
    private $traitObject;

    function setUp()
    {
        $this->traitObject = new RetriesImpl;
    }

    /** @test */
    function it_retries_without_failing()
    {
        $i = 0;
        $value = $this->traitObject->retry(1, 
            function () use (&$i) {
                $i++;
                return 5;
            });

        $this->assertSame(1, $i);
        $this->assertSame(5, $value);
    }

    /** @test */
    function it_retries_failing_once()
    {
        $i = 0;
        $failed = false;
        $value = $this->traitObject->retry(1, 
            function () use (&$i, &$failed) {
                $i++;
                if (!$failed) {
                    $failed = true;
                    throw new \RuntimeException('roflcopter');
                }
                return 5;
            });

        $this->assertSame(2, $i);
        $this->assertSame(5, $value);
    }

    /** @test */
    function it_retries_failing_too_hard()
    {
        $e = null;
        $i = 0;
        try {
            $this->traitObject->retry(1, 
                function () use (&$i) {
                    $i++;
                    throw new \RuntimeException('rofl');
                });
        } catch (\Exception $e) {
        }

        $this->assertInstanceof('Airflix\FailingTooHard', $e);
        $this->assertInstanceof('RuntimeException', $e->getPrevious());
        $this->assertSame('rofl', $e->getPrevious()->getMessage());
        $this->assertSame(2, $i);
    }

    /** @test */
    function it_retries_many_times()
    {
        $e = null;
        $i = 0;
        try {
            $this->traitObject->retry(1000, 
                function () use (&$i) {
                    $i++;
                    throw new \RuntimeException('dogecoin');
                });
        } catch (\Exception $e) {
        }

        $this->assertInstanceof('Airflix\FailingTooHard', $e);
        $this->assertInstanceof('RuntimeException', $e->getPrevious());
        $this->assertSame('dogecoin', $e->getPrevious()->getMessage());
        $this->assertSame(1001, $i);
    }
}