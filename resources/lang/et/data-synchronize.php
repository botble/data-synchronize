<?php

return [
    'tools' => [
        'export_import_data' => 'Ekspordi/impordi andmeid',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Impordi :label',
        'failed_to_read_file' => 'Fail on kehtetu, rikutud või liiga suur lugemiseks.',

        'form' => [
            'quick_export_message' => 'Kui soovite eksportida :label andmeid, saate seda kiiresti teha, klõpsates :export_csv_link või :export_excel_link.',
            'quick_export_button' => 'Ekspordi :format',
            'dropzone_message' => 'Lohistage fail siia või klõpsake üleslaadimiseks',
            'allowed_extensions' => 'Valige fail järgmiste laienditega: :extensions.',
            'import_button' => 'Impordi',
            'chunk_size' => 'Tükisuurus',
            'chunk_size_helper' => 'Korraga imporditavate ridade arv määratakse tüki suurusega. Suurendage seda väärtust, kui teil on suur fail ja andmed imporditakse väga kiiresti. Vähendage seda väärtust, kui teil tekivad mälu piirangud või lüüsi ajalõpu probleemid andmete importimisel.',
        ],

        'failures' => [
            'title' => 'Ebaõnnestumised',
            'attribute' => 'Atribuut',
            'errors' => 'Vead',
        ],

        'example' => [
            'title' => 'Näide',
            'download' => 'Laadi alla näidis :type fail',
        ],

        'rules' => [
            'title' => 'Reeglid',
            'column' => 'Veerg',
        ],

        'uploading_message' => 'Faili üleslaadimine algab...',
        'uploaded_message' => 'Fail :file on edukalt üles laaditud. Andmete valideerimine algab...',
        'validating_message' => 'Valideerimine alates :from kuni :to...',
        'importing_message' => 'Importimine alates :from kuni :to...',
        'done_message' => 'Edukalt imporditud :count :label.',
        'validating_failed_message' => 'Valideerimine ebaõnnestus. Palun kontrollige allpool olevaid vigu.',
        'no_data_message' => 'Teie andmed on juba ajakohased või importida pole midagi.',
    ],

    'export' => [
        'name' => 'Eksport',
        'heading' => 'Ekspordi :label',
        'excel_not_supported_for_large_exports' => 'Exceli formaat ei ole toetatud suurte eksportide puhul (:count üksust). Palun kasutage paremate tulemuste ja usaldusväärsuse jaoks CSV formaati.',

        'form' => [
            'all_columns_disabled' => 'Eksporditakse järgmised veerud: :columns.',
            'columns' => 'Veerud',
            'format' => 'Formaat',
            'export_button' => 'Ekspordi',
        ],

        'success_message' => 'Edukalt eksporditud.',
        'error_message' => 'Eksport ebaõnnestus.',

        'empty_state' => [
            'title' => 'Eksportida pole andmeid',
            'description' => 'Tundub, et eksportida pole andmeid.',
            'back' => 'Tagasi lehele :page',
        ],
    ],
    'check_all' => 'Vali kõik',
];
