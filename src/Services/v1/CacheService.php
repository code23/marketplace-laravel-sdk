<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchAttributes;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchBlogCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCharities;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCurrencies;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchModules;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchPopulatedCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchSpecifications;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchTags;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchVendors;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService extends Service
{
    /**
     * Retrieve stored MPE data from cache
     * - Updates stored data from the API if it's not found
     * @param string $key The name of the cache key to retrieve
     * @return mixed
     */
    function get($key)
    {
        if(Cache::has($key)) {
            return Cache::get($key);
        } else {
            switch ($key) {
                case 'attributes':
                    $job = new MPEFetchAttributes();
                    $job->handle();
                    break;

                case 'blog_categories':
                    $job = new MPEFetchBlogCategories();
                    $job->handle();
                    break;

                case 'categories':
                    $job = new MPEFetchCategories();
                    $job->handle();
                    break;

                case 'charities':
                    $job = new MPEFetchCharities();
                    $job->handle();
                    break;

                case 'currencies':
                    $job = new MPEFetchCurrencies();
                    $job->handle();
                    break;

                case 'modules':
                    $job = new MPEFetchModules();
                    $job->handle();
                    break;

                case 'populated_categories':
                    $job = new MPEFetchPopulatedCategories();
                    $job->handle();
                    break;

                case 'specifications':
                    $job = new MPEFetchSpecifications();
                    $job->handle();
                    break;

                case 'tags':
                    $job = new MPEFetchTags();
                    $job->handle();
                    break;

                case 'vendors':
                    $job = new MPEFetchVendors();
                    $job->handle();
                    break;

                default:
                    throw new Exception('Invalid cache key');
                    break;
            }
            if(Cache::has($key)) {
                return Cache::get($key);
            } else {
                Log::error('Cache key not found: ' . $key);
                throw new Exception('Cache key not found');
            }
        }
    }

    /**
     * Search active modules
     * @param string $code The module code to search for
     * @param array $params Additional parameters to pass to the API
     * @return bool
     */
    public function hasModule(String $code, $params = [])
    {
        // if modules retrieved successfully
        if ($modules = $this->get('modules', $params)) {
            // check if string is in modules
            if ($modules->contains($code)) return true;
        }

        // else return false
        return false;
    }
}
