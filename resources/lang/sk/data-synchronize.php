<?php

return [
    'tools' => [
        'export_import_data' => 'Export/Import dát',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Import :label',
        'failed_to_read_file' => 'Súbor je neplatný, poškodený alebo príliš veľký na prečítanie.',

        'form' => [
            'quick_export_message' => 'Ak chcete exportovať dáta :label, môžete to rýchlo urobiť kliknutím na :export_csv_link alebo :export_excel_link.',
            'quick_export_button' => 'Exportovať do :format',
            'dropzone_message' => 'Pretiahnite súbor sem alebo kliknite pre nahratie',
            'allowed_extensions' => 'Vyberte súbor s nasledujúcimi príponami: :extensions.',
            'import_button' => 'Importovať',
            'chunk_size' => 'Veľkosť bloku',
            'chunk_size_helper' => 'Počet riadkov, ktoré majú byť importované naraz, je definovaný veľkosťou bloku. Zvýšte túto hodnotu, ak máte veľký súbor a dáta sa importujú veľmi rýchlo. Znížte túto hodnotu, ak narazíte na obmedzenia pamäte alebo problémy s časovým limitom brány pri importe dát.',
        ],

        'failures' => [
            'title' => 'Zlyhania',
            'attribute' => 'Atribút',
            'errors' => 'Chyby',
        ],

        'example' => [
            'title' => 'Príklad',
            'download' => 'Stiahnuť príklad súboru :type',
        ],

        'rules' => [
            'title' => 'Pravidlá',
            'column' => 'Stĺpec',
        ],

        'uploading_message' => 'Začína sa nahrávanie súboru...',
        'uploaded_message' => 'Súbor :file bol úspešne nahraný. Začína sa validácia dát...',
        'validating_message' => 'Validácia od :from do :to...',
        'importing_message' => 'Importovanie od :from do :to...',
        'done_message' => 'Úspešne importovaných :count :label.',
        'validating_failed_message' => 'Validácia zlyhala. Skontrolujte prosím chyby nižšie.',
        'no_data_message' => 'Vaše dáta sú už aktuálne alebo nie sú k dispozícii žiadne dáta na import.',
    ],

    'export' => [
        'name' => 'Export',
        'heading' => 'Export :label',
        'excel_not_supported_for_large_exports' => 'Formát Excel nie je podporovaný pre veľké exporty (:count položiek). Použite formát CSV pre lepší výkon a spoľahlivosť.',

        'form' => [
            'all_columns_disabled' => 'Budú exportované nasledujúce stĺpce: :columns.',
            'columns' => 'Stĺpce',
            'format' => 'Formát',
            'export_button' => 'Exportovať',
        ],

        'success_message' => 'Úspešne exportované.',
        'error_message' => 'Export zlyhal.',

        'empty_state' => [
            'title' => 'Žiadne dáta na export',
            'description' => 'Zdá sa, že nie sú k dispozícii žiadne dáta na export.',
            'back' => 'Späť na :page',
        ],
    ],
    'check_all' => 'Vybrať všetko',
];
