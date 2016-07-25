<?php

namespace Airflix;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Dumper as YamlDumper;

class ApiResponse implements Contracts\ApiResponse
{
    /**
     * The base url for fractal transformers.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The fractal manager.
     *
     * @var \League\Fractal\Manager
     */
    protected $fractal;

    /**
     * The status code returned with an API response.
     *
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * The app codes included in an error API response.
     */
    const CODE_CONFLICT = 'GEN-EMOREN';
    const CODE_WRONG_ARGS = 'GEN-FUBARGS';
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
    const CODE_INTERNAL_ERROR = 'GEN-AAAGGH';
    const CODE_UNAUTHORIZED = 'GEN-MAYBGTFO';
    const CODE_FORBIDDEN = 'GEN-GTFO';
    const CODE_INVALID_MIME_TYPE = 'GEN-UMWUT';
    const CODE_INVALID_INPUT = 'GEN-VALIDATE';

    /**
     * Create a new ApiResponse instance.
     *
     * @param  \League\Fractal\Manager $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;

        $this->baseUrl = config('airflix.api.url');

        $this->fractal->setSerializer(
            new JsonApiSerializer($this->baseUrl)
        );
    }

    /**
     * Getter for the fractal manager.
     *
     * @return \League\Fractal\Manager
     */
    public function fractal()
    {
        return $this->fractal;
    }

    /**
     * Getter for statusCode.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * Setter for statusCode.
     *
     * @param  int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond with an item.
     *
     * @param  mixed $item
     * @param  \League\Fractal\TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithItem($item, $transformer)
    {
        $resource = new Item(
            $item,
            $transformer,
            $transformer->resourceType
        );

        $resource->setMetaValue(
            'version',
            $transformer->resourceVersion
        );

        $rootScope = $this->fractal
            ->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Respond with a collection.
     *
     * @param  \Illuminate\Support\Collection $collection
     * @param  \League\Fractal\TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithCollection($collection, $transformer)
    {
        $resource = new Collection(
            $collection,
            $transformer,
            $transformer->resourceType
        );

        $resource->setMetaValue(
            'version',
            $transformer->resourceVersion
        );

        $rootScope = $this->fractal
            ->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Respond with a collection.
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator $paginator
     * @param  \League\Fractal\TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithPaginator($paginator, $transformer)
    {
        $resource = new Collection(
            $paginator->getCollection(),
            $transformer,
            $transformer->resourceType
        );

        $resource->setMetaValue(
            'version',
            $transformer->resourceVersion
        );

        $resource->setPaginator(
            new IlluminatePaginatorAdapter($paginator)
        );

        $rootScope = $this->fractal
            ->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Generate a response from an array.
     *
     * @param  array $array
     * @param  array $headers
     *
     * @return \Illuminate\Http\Response
     */
    public function respondWithArray($array, $headers = [])
    {
        $mimeType = request()->server('HTTP_ACCEPT', '*/*');

        $mimeParts = (array) array_filter(
            explode(';', $mimeType), 'strlen'
        );

        // If it has */* then default to JSON response
        if (str_contains($mimeType, '*/*') ||
                str_contains($mimeType, 'application/json')) {
            $mimeType = 'application/vnd.api+json';
        } else {
            $mimeType = strtolower($mimeParts[0]);
        }

        $contentType = isset($array['meta']) ?
            'version='.$array['meta']['version'].'; ' : '';
        $contentType .= 'charset=utf-8';

        switch ($mimeType) {
            case 'application/vnd.api+json':
                $contentType = 'application/vnd.api+json; '.$contentType;

                $content = json_encode($array);

                break;

            case 'application/x-yaml':
                $contentType = 'application/x-yaml; '.$contentType;

                $dumper = new YamlDumper();

                $content = $dumper->dump($array, 2);

                break;

            default:
                $this->setStatusCode(415);

                $contentType = 'application/vnd.api+json; '.$contentType;

                $content = json_encode([
                    'error' => [
                        'status' => 415,
                        'code' => static::CODE_INVALID_MIME_TYPE,
                        'message' => sprintf(
                            'Content of type %s is not supported.',
                            $mimeType
                        ),
                    ]
                ]);
        }

        $response = response()->make($content, $this->statusCode, $headers);

        $response->header('Content-Type', $contentType);

        return $response;
    }

    /**
     * Respond with an error.
     *
     * @param  string $message
     * @param  string $errorCode
     *
     * @return \Illuminate\Http\Response
     */
    protected function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason '.
                'for erroring on a 200 status...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'errors' => [
                [
                    'status' => $this->statusCode,
                    'code' => $errorCode,
                    'message' => $message,
                ],
            ]
        ]);
    }

    /**
     * Generate a response with a 409 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorConflict($message = 'Conflict')
    {
        return $this->setStatusCode(409)
            ->respondWithError($message, self::CODE_CONFLICT);
    }

    /**
     * Generate a response with a 403 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generate a response with a 500 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }
    
    /**
     * Generate a response with a 404 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generate a response with a 401 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generate a response with a 400 HTTP header.
     *
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * Generate a response with a 422 HTTP header.
     *
     * @param  \Illuminate\Http\Response $response
     * @param  string $message
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorValidation($response, $message = 'Validation failed')
    {
        $this->setStatusCode(422);

        return $this->respondWithArray([
            'data' => $response->getData(),
            'errors' => [
                [
                    'status' => $this->statusCode,
                    'code' => self::CODE_INVALID_INPUT,
                    'title' => $message,
                ],
            ],
        ]);
    }
    /**
     * Render an exception response.
     *
     * @param  \Exception $e
     * 
     * @return \Illuminate\Http\Response
     */
    public function renderException($e)
    {
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof ModelNotFoundException) {
            return $this->errorNotFound();
        } elseif ($e instanceof NotFoundHttpException) {
            return $this->errorNotFound();
        } elseif ($e instanceof AuthorizationException) {
            return $this->errorForbidden($e->getMessage());
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            return $this->errorValidation($e->getResponse());
        }

        throw $e;
        
        return $this->errorInternalError();
    }
}
