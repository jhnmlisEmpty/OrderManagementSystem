<?php

return [

    'asset_url' => null,

    'middleware' => [
        'web',
    ],

    'component_path' => resource_path('livewire'),

    'temporary_file_disk' => 'public', // Changed to 'public'

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_DISK', 'public'), // Ensure 'public' is being used
        'rules' => null,
        'directory' => null,
        'middleware' => null,
    ],

    'view_path' => resource_path('views/livewire'),

    'debug' => env('LIVEWIRE_DEBUG', false),

    'file_uploads' => [
        'disk' => 'public',
        'storage_path' => 'livewire-tmp',
        'max_file_size' => 1024, // in KB
    ],

    'preserve_state' => true,

];
