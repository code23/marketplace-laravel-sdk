<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class BlogService extends Service
{
    /**
     * Get a list of blog categories
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function categories($params = [
        'is_active' => true
    ], $oauth)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/blog/categories', $params);

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the blog categories.', 422);

        // if successful, return blog categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of blog posts
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function posts(array $params = [
            'with' => 'blog_categories,images',
            'paginate' => 12,
            'page' => 1,
            'sort' => 'published_at,desc',
        ],
        $oauth,
    ) {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/blog/posts', $params);

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception($response->body(), 422);

        // if successful, return blog posts as collection
        if (isset($params['paginate']) && $params['paginate']) return $response->json() ? collect($response->json()) : collect();
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
