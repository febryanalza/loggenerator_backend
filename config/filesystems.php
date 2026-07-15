<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    | === SUPABASE STORAGE (S3-Compatible) ===
    | Supabase Storage exposes an S3-compatible API. Key differences vs. AWS:
    |   1. Requires a custom endpoint:  https://<project-ref>.supabase.co/storage/v1/s3
    |   2. Requires path-style endpoint: use_path_style_endpoint = true
    |   3. Public URL pattern (for public buckets):
    |      https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>/<path>
    |   4. ACL/visibility options per-object are NOT supported — public access
    |      is controlled by the bucket's RLS policy in Supabase Dashboard.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app/private'),
            'serve'  => true,
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Main S3 Disk
        |--------------------------------------------------------------------------
        | General-purpose Supabase S3-compatible disk.
        | Files are stored at the bucket root.
        |
        | The `url` key defines the base URL used by Storage::disk()->url().
        | For Supabase public buckets, the public URL is:
        |   https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>/<path>
        */
        's3' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION', 'ap-northeast-1'),
            'bucket'                  => env('AWS_BUCKET'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'url'                     => env('AWS_URL'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'throw'                   => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Avatar Disk
        |--------------------------------------------------------------------------
        | Dedicated disk for user avatar images.
        | Files are stored under the 'avatars/' folder within the Supabase bucket.
        |
        | Public URL pattern:
        |   https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>/avatars/<filename>
        */
        's3_avatars' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION', 'ap-northeast-1'),
            'bucket'                  => env('AWS_BUCKET'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'url'                     => env('AWS_URL'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'root'                    => 'avatars',
            'throw'                   => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Logbook Images Disk
        |--------------------------------------------------------------------------
        | Dedicated disk for logbook entry field images.
        | Files are stored under the 'logbook_images/' folder within the Supabase bucket.
        |
        | Public URL pattern:
        |   https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>/logbook_images/<filename>
        */
        's3_logbook' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION', 'ap-northeast-1'),
            'bucket'                  => env('AWS_BUCKET'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'url'                     => env('AWS_URL'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'root'                    => 'logbook_images',
            'throw'                   => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
