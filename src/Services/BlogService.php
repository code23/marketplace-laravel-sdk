<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Support\Str;

class BlogService extends Service
{
    /**
     * Get a list of blog posts
     *
     */
    public function posts()
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/posts', ['with' => 'images,blog_categories']);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the blog posts.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog posts as collection
        if($response->json()['data']) {
            return collect($response->json()['data'])
                        ->where('status', 'published');
        }

        // if no results
        return collect();
    }

    /**
     * Get a list of blog categories with their posts
     *
     */
    public function categories()
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/categories', ['with' => 'blog_posts']);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the blog categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog categories as collection
        if($response->json()['data']) {
            return collect($response->json()['data']);
        }

        // if no results
        return collect();
    }

    /**
     * Get a list of posts by blog category
     *
     */
    public function postsByCategory($id)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/categories/' . $id, ['with' => 'blog_posts']);

        // category not found
        if($response->status() == 404) throw new Exception('The given blog category was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the posts by blog category.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return posts as collection
        return $response->json()['data'] ? collect($response->json()['data']['blog_posts'])->where('status', 'published') : collect();
    }

    /**
     * Get a single blog post
     *
     */
    public function showPost($id)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/posts/' . $id, ['with' => 'blog_categories,images']);

        // blog post not found
        if($response->status() == 404) throw new Exception('The given blog post was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the blog post.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog post as collection
        if($response->json()['data']) {
            return collect($response->json()['data']);
        }
    }
}
