<?php

return [
    'mime_types' => [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'application/csv',
        'text/plain',
    ],
    'extensions' => [
        'csv',
        'xls',
        'xlsx',
    ],
    'storage' => [
        'disk' => 'local',
        'path' => 'data-synchronize',
    ],
    'export' => [
        'chunk_size' => env('EXPORT_CHUNK_SIZE', 400),
        'memory_limit' => env('EXPORT_MEMORY_LIMIT', '512M'),
        'time_limit' => env('EXPORT_TIME_LIMIT', 0),
        'optimize_memory' => env('EXPORT_OPTIMIZE_MEMORY', true),
        'use_chunked' => env('EXPORT_USE_CHUNKED', true),
    ],
];
