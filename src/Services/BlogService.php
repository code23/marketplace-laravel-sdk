<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class BlogService extends Service
{
    /**
     * Get a list of blog posts
     *
     * @param string $with - optional - relationships to include, comma separated 'images,blog_categories'
     *
     */
    public function posts($with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/posts', ['with' => $with]);

        // api call failed
        if ($response->failed()) throw new Exception($response->body(), 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog posts as collection
        return $response->json()['data'] ? collect($response->json()['data'])->where('status', 'published') : collect();
    }

    /**
     * Get a blog category by slug with its posts
     *
     * @param string $with - optional - relationships to include, comma separated 'blog_posts,foobar'
     *
     */
    public function categoryBySlug($slug, String $with = 'blog_posts.images,blog_categories')
    {
        $params = [
            'is_active' => 1,
            'with'   => $with,
        ];

        // send request
        $response = $this->http()->get($this->getPath() . '/blog/categories/slug/' . $slug, $params);

        // api call failed
        // if ($response->failed()) throw new Exception('Error attempting to retrieve the blog category.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of blog categories
     *
     * @param string $with - optional - relationships to include, comma separated 'blog_posts,foobar'
     *
     */
    public function categories(String $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/categories', [
            'with' => $with,
            'is_active' => true
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the blog categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of posts by blog category
     *
     * @param string $with - optional - relationships to include, comma separated 'blog_posts.images,foobar'
     */
    public function postsByCategory(Int $id, string $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/categories/' . $id, ['with' => $with]);

        // category not found
        if($response->status() == 404) throw new Exception('The given blog category was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the posts by blog category.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return posts as collection
        return $response->json()['data'] ? collect($response->json()['data']['blog_posts'])->where('status', 'published') : collect();
    }

    /**
     * Get a single blog post
     *
     * @param int $id - post id to get
     *
     * @param string $with - optional - relationships to include, comma separated 'images,foobar'
     */
    public function getPost($id, $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/posts/' . $id, ['with' => $with]);

        // blog post not found
        if($response->status() == 404) throw new Exception('The given blog post was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the blog post.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog post as collection
        if($response->json()['data']) return collect($response->json()['data']);
    }

    /**
     * Get a single blog post by slug
     *
     * @param string $slug - post slug to get
     *
     * @param string $with - optional - relationships to include, comma separated 'images,foobar'
     */
    public function getPostBySlug($slug, $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/blog/posts/slug/' . $slug, ['with' => $with]);

        // blog post not found
        if($response->status() == 404) throw new Exception('The given blog post was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the blog post.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog post as collection
        if($response->json()['data']) return collect($response->json()['data']);
    }
}
