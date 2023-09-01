<?php

namespace Code23\MarketplaceLaravelSDK\Http\Controllers\v1;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SignedStorageUrlController extends Controller
{
    /**
     * Create a new signed URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->ensureEnvironmentVariablesAreAvailable($request);

        $bucket = $request->input('bucket') ?: config('marketplace-laravel-sdk.s3.bucket');

        $client = $this->storageClient();

        $uuid = (string) Str::uuid();

        $expiresAfter = config('marketplace-laravel-sdk.s3.signed_storage_url_expires_after', 5);

        $signedRequest = $client->createPresignedRequest(
            $this->createCommand($request, $client, $bucket, $key = ('tmp/'.$uuid)),
            sprintf('+%s minutes', $expiresAfter)
        );

        $uri = $signedRequest->getUri();

        return response()->json([
            'uuid' => $uuid,
            'bucket' => $bucket,
            'key' => $key,
            'url' => $uri->getScheme().'://'.$uri->getAuthority().$uri->getPath().'?'.$uri->getQuery(),
            'headers' => $this->headers($request, $signedRequest),
        ], 201);
    }

    /**
     * Create a command for the PUT operation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Aws\S3\S3Client  $client
     * @param  string  $bucket
     * @param  string  $key
     * @return \Aws\Command
     */
    protected function createCommand(Request $request, S3Client $client, $bucket, $key)
    {
        return $client->getCommand('putObject', array_filter([
            'Bucket' => $bucket,
            'Key' => $key,
            'ACL' => $request->input('visibility') ?: $this->defaultVisibility(),
            'ContentType' => $request->input('content_type') ?: 'application/octet-stream',
            'CacheControl' => $request->input('cache_control') ?: null,
            'Expires' => $request->input('expires') ?: null,
        ]));
    }

    /**
     * Get the headers that should be used when making the signed request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \GuzzleHttp\Psr7\Request
     * @return array
     */
    protected function headers(Request $request, $signedRequest)
    {
        return array_merge(
            $signedRequest->getHeaders(),
            [
                'Content-Type' => $request->input('content_type') ?: 'application/octet-stream',
            ]
        );
    }

    /**
     * Ensure the required environment variables are available.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function ensureEnvironmentVariablesAreAvailable(Request $request)
    {
        if (
            is_null(config('marketplace-laravel-sdk.s3.bucket')) ||
            is_null(config('marketplace-laravel-sdk.s3.region')) ||
            is_null(config('marketplace-laravel-sdk.s3.key')) ||
            is_null(config('marketplace-laravel-sdk.s3.secret'))
        ) {
            throw new InvalidArgumentException(
                'Unable to issue signed URL. Check environment variables exist: AWS_BUCKET, AWS_DEFAULT_REGION, AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY'
            );
        }

        return;
    }

    /**
     * Get the S3 storage client instance.
     *
     * @return \Aws\S3\S3Client
     */
    protected function storageClient()
    {
        $config = [
            'region' => config('marketplace-laravel-sdk.s3.region'),
            'version' => 'latest',
            'signature_version' => 'v4',
            'use_path_style_endpoint' => config('marketplace-laravel-sdk.s3.use_path_style_endpoint', false),
        ];

        if (! config('marketplace-laravel-sdk.s3.lamda_function_version')) {
            $config['credentials'] = array_filter([
                'key' => config('marketplace-laravel-sdk.s3.key') ?? null,
                'secret' => config('marketplace-laravel-sdk.s3.secret') ?? null,
                'token' => config('marketplace-laravel-sdk.s3.token') ?? null,
            ]);

            if (config('marketplace-laravel-sdk.s3.url')) {
                $config['url'] = config('marketplace-laravel-sdk.s3.url');
                $config['endpoint'] = config('marketplace-laravel-sdk.s3.url');
            }
        }

        return S3Client::factory($config);
    }

    /**
     * Get the default visibility for uploads.
     *
     * @return string
     */
    protected function defaultVisibility()
    {
        return 'private';
    }
}
