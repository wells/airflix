<?php

namespace Airflix;

class FailingTooHard extends \Exception {}

trait Retriable {
    /**
     * Retry a callback a number of times.
     *
     * @param  integer $retries 
     * @param  callable $fn      
     * @param  callable|null $onError
     *
     * @return mixed
     */
    function retry($retries, callable $fn, callable $onError = null)
    {
        beginning:
        try {
            return $fn();
        } catch (\Exception $e) {
            if ($onError) {
                $onError($e);
            }
            if (!$retries) {
                throw new FailingTooHard('', 0, $e);
            }
            $retries--;
            goto beginning;
        }
    }
}