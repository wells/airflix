<?php

namespace Airflix\Contracts;

interface ApiResponse
{
    public function fractal();
    public function getStatusCode();
    public function setStatusCode($statusCode);
    public function respondWithItem($item, $transformer);
    public function respondWithCollection($collection, $transformer);
    public function respondWithPaginator($paginator, $transformer);
    public function respondWithArray($array, $headers);
    public function errorForbidden($message);
    public function errorInternalError($message);
    public function errorNotFound($message);
    public function errorUnauthorized($message);
    public function errorWrongArgs($message);
    public function errorValidation($response, $message);
}
