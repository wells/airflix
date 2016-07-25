<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Jobs\RefreshAll;
use App\Jobs\RefreshNew;
use Illuminate\Http\Request;

class FoldersController extends ApiController
{
    /**
     * Refresh the folders, either all or just new folders.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request)
    {
        if (isset($request['all'])) {
            $this->dispatch(new RefreshAll());
        } else {
            $this->dispatch(new RefreshNew());
        }

        return $this->apiResponse()
            ->respondWithArray([]);
    }
}
