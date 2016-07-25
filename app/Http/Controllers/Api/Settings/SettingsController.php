<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Jobs\RefreshAll;
use Airflix\Contracts\Settings;
use Artisan;
use Illuminate\Http\Request;

class SettingsController extends ApiController
{
    /**
     * Inject the settings resource.
     *
     * @return \Airflix\Contracts\Settings
     */
    protected function settings()
    {
        return app()->make(Settings::class);
    }

    /**
     * Get the settings for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->apiResponse()
            ->respondWithArray(
                $this->settings()
                    ->get()
            );
    }

    /**
     * Set the folder paths for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request)
    {
        $this->validate($request, [
            'data.attributes.movies_folder' =>
                'required_without:data.attributes.shows_folder|string',
            'data.attributes.shows_folder' =>
                'required_without:data.attributes.movies_folder|string',
        ]);

        $input = $request->input();
        $type = 'settings';

        // 403 - Unsupported request format
        if (! $this->validRequestStructure($input)) {
            return $this->apiResponse()
                ->errorForbidden(
                    'Unsupported request format (requires JSON-API).'
                );
        }

        // 409 - Incorrect id or type
        if (! $this->validRequestData($input, 0, $type)) {
            return $this->apiResponse()
                ->errorConflict(
                    'Bad request data (verify id and type).'
                );
        }

        $attributes = $request->input('data.attributes');

        if (isset($attributes['movies_folder'])) {
            $options['--movies'] = $attributes['movies_folder'];
        }

        if (isset($attributes['shows_folder'])) {
            $options['--shows'] = $attributes['shows_folder'];
        }

        $exitCode = Artisan::call('airflix:folders', $options);

        if ($exitCode == 255) {
            $this->dispatch(new RefreshAll());
        }

        return $this->apiResponse()
            ->respondWithArray(
                $this->settings()
                    ->get()
            );
    }
}
