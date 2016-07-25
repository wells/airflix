<?php

namespace Airflix;

use Illuminate\Database\Eloquent\Model;

class EpisodeView extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'show_id',
        'season_id',
        'episode_id',
        'show_uuid',
        'season_uuid',
        'episode_uuid',
        'tmdb_show_id',
    ];
}
