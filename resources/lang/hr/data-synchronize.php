<?php

return [
    'tools' => [
        'export_import_data' => 'Izvezi/Uvezi podatke',
    ],

    'import' => [
        'name' => 'Uvoz',
        'heading' => 'Uvezi :label',
        'failed_to_read_file' => 'Datoteka je nevažeća, oštećena ili prevelika za čitanje.',

        'form' => [
            'quick_export_message' => 'Ako želite izvesti :label podatke, možete to brzo učiniti klikom na :export_csv_link ili :export_excel_link.',
            'quick_export_button' => 'Izvezi u :format',
            'dropzone_message' => 'Povucite i ispustite datoteku ovdje ili kliknite za učitavanje',
            'allowed_extensions' => 'Odaberite datoteku sa sljedećim ekstenzijama: :extensions.',
            'import_button' => 'Uvezi',
            'chunk_size' => 'Veličina dijela',
            'chunk_size_helper' => 'Broj redaka koji će biti uvezeni odjednom definiran je veličinom dijela. Povećajte ovu vrijednost ako imate veliku datoteku i podaci se uvoze vrlo brzo. Smanjite ovu vrijednost ako naiđete na ograničenja memorije ili probleme s istek vremena pristupnika prilikom uvoza podataka.',
        ],

        'failures' => [
            'title' => 'Neuspjesi',
            'attribute' => 'Atribut',
            'errors' => 'Greške',
        ],

        'example' => [
            'title' => 'Primjer',
            'download' => 'Preuzmi primjer :type datoteke',
        ],

        'rules' => [
            'title' => 'Pravila',
            'column' => 'Stupac',
        ],

        'uploading_message' => 'Započinje učitavanje datoteke...',
        'uploaded_message' => 'Datoteka :file je uspješno učitana. Započinje validacija podataka...',
        'validating_message' => 'Validacija od :from do :to...',
        'importing_message' => 'Uvoz od :from do :to...',
        'done_message' => 'Uspješno uvezeno :count :label.',
        'validating_failed_message' => 'Validacija nije uspjela. Provjerite greške ispod.',
        'no_data_message' => 'Vaši podaci su već ažurirani ili nema podataka za uvoz.',
    ],

    'export' => [
        'name' => 'Izvoz',
        'heading' => 'Izvezi :label',
        'excel_not_supported_for_large_exports' => 'Excel format nije podržan za velike izvoz (:count stavki). Molimo koristite CSV format umjesto toga za bolju izvedbu i pouzdanost.',

        'form' => [
            'all_columns_disabled' => 'Sljedeći stupci će biti izvezeni: :columns.',
            'columns' => 'Stupci',
            'format' => 'Format',
            'export_button' => 'Izvezi',
        ],

        'success_message' => 'Uspješno izvezeno.',
        'error_message' => 'Izvoz nije uspio.',

        'empty_state' => [
            'title' => 'Nema podataka za izvoz',
            'description' => 'Čini se da nema podataka za izvoz.',
            'back' => 'Natrag na :page',
        ],
    ],
    'check_all' => 'Označi sve',
];
