<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class SitemapService extends Service
{
    /**
     * Get the site's content from MPE suitable for sitemap
     *
     * @param [type] $oauth
     */
    public function getSitemapContent($oauth)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/sitemap-content');

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the sitemap content.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return data as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
