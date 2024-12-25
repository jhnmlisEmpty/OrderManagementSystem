<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Assets URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that Livewire's assets (JavaScript and CSS) will be served from.
    |
    */

    'asset_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Middleware
    |--------------------------------------------------------------------------
    |
    | You can specify middleware here to apply to all Livewire requests. Livewire
    | will apply these middleware to any requests that it makes.
    |
    */

    'middleware' => [
        'web',
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components Path
    |--------------------------------------------------------------------------
    |
    | This path is where Livewire will look for your components.
    |
    */

    'component_path' => resource_path('livewire'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Temporary File Disk
    |--------------------------------------------------------------------------
    |
    | This is the disk that Livewire will use to store temporary files.
    |
    */

    'temporary_file_disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Livewire View Path
    |--------------------------------------------------------------------------
    |
    | This is the directory where Livewire's view files are stored.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Debugging
    |--------------------------------------------------------------------------
    |
    | When enabled, Livewire will log helpful debug information to the browser
    | console. This can be useful while developing your Livewire components.
    |
    */

    'debug' => env('LIVEWIRE_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Livewire File Uploads
    |--------------------------------------------------------------------------
    |
    | Here you can configure various settings related to file uploads in Livewire.
    |
    */

    'file_uploads' => [
        'disk' => 'public',
        'storage_path' => 'livewire-tmp',
        'max_file_size' => 1024, // in KB
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Preserved State
    |--------------------------------------------------------------------------
    |
    | This setting controls whether Livewire should preserve the component's
    | state between requests.
    |
    */

    'preserve_state' => true,

];
