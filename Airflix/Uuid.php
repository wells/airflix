<?php

namespace Airflix;

trait Uuid
{
	/**
	 * Fill a uuid attribute to a model on creation.
	 */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->attributes['uuid'] = (string) \Ramsey\Uuid\Uuid::uuid4();

        });
    }
}
