<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in
    | your application. This value changes where component auto-discovery finds
    | components. It's also referenced by the file creation commands.
    |
    */

    'class_namespace' => 'App\\Http\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path for Livewire component views. This value changes
    | where Livewire component auto-discovery finds component views. It's also
    | referenced by the file creation commands.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | The default layout used when rendering a single component as a full page.
    | This can be overridden on a per component basis by defining a `layout`
    | property on the component class.
    */

    /*
    |--------------------------------------------------------------------------
    | Temporary File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is stored permanently. All file uploads are directed to
    | the disk and directory you configure below.
    |
    */

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_DISK', 'local'),
        'rules' => null,
        'directory' => null,
        'middleware' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Manifest File Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path to the Livewire manifest file. This file is used
    | by Livewire to keep track of which components are being used in the app.
    | This file should be included in your version control system.
    |
    */

    'manifest_path' => storage_path('framework/livewire-manifest.php'),

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether the back button cache will be used on pages
    | that contain Livewire components. By disabling back button cache, it can
    | help prevent stale data when navigating back to a previous page.
    |
    */

    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines whether Livewire will render before it's redirected
    | or not. Setting it to "false" (default) will mean the render method is
    | skipped when redirecting. And "true" will mean the render method is run.
    |
    */

    'render_on_redirect' => false,

];