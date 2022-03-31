<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class LocaleService extends Service
{
    /**
     * Returns the active locale from the session.
     */
    public function active()
    {
        return (session('locales') && session('active_locale_id'))
            ? session('locales')->firstWhere('id', session('active_locale_id'))
            : null;
    }

    /**
     * Retrieve enabled locales from API
     */
    public function list()
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/settings/locales');

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst retrieving the locales.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']['selected_locales'])->where('is_enabled', true) : collect();
    }

    /**
     * Retrieves enabled locales from API & stores them in session.
     */
    public function init()
    {
        // if no locales in session
        if(!session('locales')) {

            // retrieve and write locales collection to session
            session(['locales' => $this->list()->map(function ($locale) {
                return [
                    'id'               => $locale['id'],
                    'country'          => $locale['country'],
                    'is_enabled'       => $locale['is_enabled'],
                    'is_default'       => $locale['is_default'],
                    'label'            => $locale['label'],
                ];
            })]);

        }

        // if active_locale_id not in session
        if(!session('active_locale_id')) {
            // set to default locale for site
            session(['active_locale_id' => session('locales')->firstWhere('is_default', true)['id']]);
        }
    }

    /**
     * Sets the active locale in user's session
     *
     * @param int $id A locale id 
     *
     * @return boolean
     */
    public function setActiveById(Int $id)
    {
        // get the locales from the user session if available, or API
        $locales = session('locales') ?? $this->list();

        // if not found
        if(!$locales) return false;

        // if id not found in available locales 
        if(!$locales->firstWhere('id', $id)) return false;

        // update active locale in session
        session(['active_locale_id' => $id]);

        return true;
    }
}
