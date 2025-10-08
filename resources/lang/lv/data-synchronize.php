<?php

return [
    'tools' => [
        'export_import_data' => 'Eksportēt/Importēt datus',
    ],

    'import' => [
        'name' => 'Importēt',
        'heading' => 'Importēt :label',
        'failed_to_read_file' => 'Fails ir nederīgs, bojāts vai pārāk liels, lai to nolasītu.',

        'form' => [
            'quick_export_message' => 'Ja vēlaties eksportēt :label datus, varat to ātri izdarīt, noklikšķinot uz :export_csv_link vai :export_excel_link.',
            'quick_export_button' => 'Eksportēt uz :format',
            'dropzone_message' => 'Velciet un nometiet failu šeit vai noklikšķiniet, lai augšupielādētu',
            'allowed_extensions' => 'Izvēlieties failu ar šādiem paplašinājumiem: :extensions.',
            'import_button' => 'Importēt',
            'chunk_size' => 'Daļas izmērs',
            'chunk_size_helper' => 'Vienlaikus importējamo rindu skaitu nosaka daļas izmērs. Palieliniet šo vērtību, ja jums ir liels fails un dati tiek importēti ļoti ātri. Samaziniet šo vērtību, ja saskaraties ar atmiņas ierobežojumiem vai vārtejas noildzes problēmām, importējot datus.',
        ],

        'failures' => [
            'title' => 'Kļūmes',
            'attribute' => 'Atribūts',
            'errors' => 'Kļūdas',
        ],

        'example' => [
            'title' => 'Piemērs',
            'download' => 'Lejupielādēt piemēra :type failu',
        ],

        'rules' => [
            'title' => 'Noteikumi',
            'column' => 'Kolonna',
        ],

        'uploading_message' => 'Sākas faila augšupielāde...',
        'uploaded_message' => 'Fails :file ir veiksmīgi augšupielādēts. Sākas datu validācija...',
        'validating_message' => 'Validācija no :from līdz :to...',
        'importing_message' => 'Importēšana no :from līdz :to...',
        'done_message' => 'Veiksmīgi importēti :count :label.',
        'validating_failed_message' => 'Validācija neizdevās. Lūdzu, pārbaudiet kļūdas zemāk.',
        'no_data_message' => 'Jūsu dati jau ir atjaunināti vai nav datu importēšanai.',
    ],

    'export' => [
        'name' => 'Eksportēt',
        'heading' => 'Eksportēt :label',
        'excel_not_supported_for_large_exports' => 'Excel formāts nav atbalstīts lieliem eksportiem (:count vienumi). Lūdzu, izmantojiet CSV formātu labākai veiktspējai un uzticamībai.',

        'form' => [
            'all_columns_disabled' => 'Tiks eksportētas šādas kolonnas: :columns.',
            'columns' => 'Kolonnas',
            'format' => 'Formāts',
            'export_button' => 'Eksportēt',
        ],

        'success_message' => 'Veiksmīgi eksportēts.',
        'error_message' => 'Eksportēšana neizdevās.',

        'empty_state' => [
            'title' => 'Nav datu eksportēšanai',
            'description' => 'Izskatās, ka nav datu eksportēšanai.',
            'back' => 'Atpakaļ uz :page',
        ],
    ],
    'check_all' => 'Atzīmēt visu',
];
