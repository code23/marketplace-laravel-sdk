<?php

namespace Code23\MarketplaceLaravelSDK\Services;

class ImageService extends Service
{

    /**
     * Return just the image_paths array of the featured image in an images object
     *
     * @param $images the images object to be filtered
     */
    public function imagesFeaturedFilter($images)
    {
        // return different image sizes paths if found, else null
        return collect($images)->where('featured', true)->first()['image_paths'] ?? null;
    }

    /**
     * Return just the image_paths array of an images object, for a given image category
     *
     * @param $images the images object to be filtered
     * @param String $category - e.g. 'logo'
     */
    public function imagesPathsFilter($images, String $category)
    {
        // return different image sizes paths if found, else null
        return collect($images)->where('category', $category)->first()['image_paths'] ?? null;
    }

    /**
     * Prepare an image object for uploading to MPE.
     *
     * Works with standard form file uploads (Illuminate\Http\UploadedFile) and Livewire (Livewire\TemporaryUploadedFile)
     */
    public function prepareImageObject(Object $image)
    {
        // return image as base64 string with data: prefix and mimetype
        return 'data:' . $image->getMimeType() . ';base64,' . base64_encode($image->get());
    }

    /**
     * register an image with MPE
     */
    public function register($image, $s3response)
    {
        // call
        $response = $this->http()->post($this->getPath() . '/images/register', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to update address!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
