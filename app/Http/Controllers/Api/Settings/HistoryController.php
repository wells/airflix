<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Jobs\ClearHistory;
use App\Jobs\ClearHistoryToday;
use Illuminate\Http\Request;

class HistoryController extends ApiController
{
    /**
     * Clear the history, either all or just from today.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(isset($request['all'])) {
            $this->dispatch(new ClearHistory());
        } else {
            $this->dispatch(new ClearHistoryToday());
        }       

        return $this->apiResponse()
            ->respondWithArray([]);
    }
}
