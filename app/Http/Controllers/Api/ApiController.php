<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Airflix\Contracts\ApiResponse;

class ApiController extends Controller
{
    /**
     * Inject the api response factory.
     *
     * @return \Airflix\Contracts\ApiResponse
     */
    protected function apiResponse()
    {
        return app(ApiResponse::class);
    }

    /**
     * Test if request structure is valid JSON-API
     *
     * @param  array $input
     *
     * @return bool
     */
    protected function validRequestStructure($input)
    {
        return isset($input['data']) &&
            isset($input['data']['type']) &&
            isset($input['data']['id']) &&
            isset($input['data']['attributes']);
    }

    /**
     * Test if request id and type are valid
     *
     * @param  array $input
     * @param  string $id
     * @param  string $type
     *
     * @return bool
     */
    protected function validRequestData($input, $id, $type)
    {
        return $input['data']['type'] == $type &&
            $input['data']['id'] == $id;
    }
}
