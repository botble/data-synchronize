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
];
