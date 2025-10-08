<?php

return [
    'tools' => [
        'export_import_data' => 'Mag-export/Mag-import ng Data',
    ],

    'import' => [
        'name' => 'Mag-import',
        'heading' => 'Mag-import ng :label',
        'failed_to_read_file' => 'Ang file ay hindi wasto, sira o masyadong malaki para basahin.',

        'form' => [
            'quick_export_message' => 'Kung gusto mong mag-export ng :label data, maaari mong gawin ito nang mabilis sa pamamagitan ng pag-click sa :export_csv_link o :export_excel_link.',
            'quick_export_button' => 'Mag-export sa :format',
            'dropzone_message' => 'I-drag at i-drop ang file dito o mag-click upang mag-upload',
            'allowed_extensions' => 'Pumili ng file na may sumusunod na mga extension: :extensions.',
            'import_button' => 'Mag-import',
            'chunk_size' => 'Laki ng chunk',
            'chunk_size_helper' => 'Ang bilang ng mga row na iimport sa isang pagkakataon ay tinutukoy ng laki ng chunk. Dagdagan ang halagang ito kung mayroon kang malaking file at ang data ay nai-import nang napakabilis. Bawasan ang halagang ito kung nakakaranas ka ng mga limitasyon sa memory o mga isyu sa gateway timeout kapag nag-iimport ng data.',
        ],

        'failures' => [
            'title' => 'Mga Pagkabigo',
            'attribute' => 'Katangian',
            'errors' => 'Mga Error',
        ],

        'example' => [
            'title' => 'Halimbawa',
            'download' => 'Mag-download ng halimbawang :type file',
        ],

        'rules' => [
            'title' => 'Mga Tuntunin',
            'column' => 'Kolum',
        ],

        'uploading_message' => 'Nagsisimula ang pag-upload ng file...',
        'uploaded_message' => 'Ang file na :file ay matagumpay na na-upload. Magsisimula sa pagvalidate ng data...',
        'validating_message' => 'Nagva-validate mula :from hanggang :to...',
        'importing_message' => 'Nag-iimport mula :from hanggang :to...',
        'done_message' => 'Matagumpay na nai-import ang :count :label.',
        'validating_failed_message' => 'Nabigo ang pagvalidate. Pakisuri ang mga error sa ibaba.',
        'no_data_message' => 'Ang iyong data ay napapanahon na o walang data na iimport.',
    ],

    'export' => [
        'name' => 'Mag-export',
        'heading' => 'Mag-export ng :label',
        'excel_not_supported_for_large_exports' => 'Ang Excel format ay hindi suportado para sa malalaking export (:count items). Mangyaring gumamit ng CSV format para sa mas mahusay na performance at reliability.',

        'form' => [
            'all_columns_disabled' => 'Ang mga sumusunod na kolum ay ie-export: :columns.',
            'columns' => 'Mga Kolum',
            'format' => 'Format',
            'export_button' => 'Mag-export',
        ],

        'success_message' => 'Matagumpay na nai-export.',
        'error_message' => 'Nabigo ang pag-export.',

        'empty_state' => [
            'title' => 'Walang data na ie-export',
            'description' => 'Mukhang walang data na ie-export.',
            'back' => 'Bumalik sa :page',
        ],
    ],
    'check_all' => 'Suriin lahat',
];
