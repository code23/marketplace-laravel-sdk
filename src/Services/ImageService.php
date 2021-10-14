<?php

namespace Code23\MarketplaceLaravelSDK\Services;

class ImageService extends Service
{
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
}
