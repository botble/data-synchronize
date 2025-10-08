<?php

return [
    'tools' => [
        'export_import_data' => 'Export/Import dat',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Import :label',
        'failed_to_read_file' => 'Soubor je neplatný, poškozený nebo příliš velký na přečtení.',

        'form' => [
            'quick_export_message' => 'Pokud chcete exportovat data :label, můžete to rychle provést kliknutím na :export_csv_link nebo :export_excel_link.',
            'quick_export_button' => 'Exportovat do :format',
            'dropzone_message' => 'Přetáhněte soubor sem nebo klikněte pro nahrání',
            'allowed_extensions' => 'Vyberte soubor s následujícími příponami: :extensions.',
            'import_button' => 'Importovat',
            'chunk_size' => 'Velikost bloku',
            'chunk_size_helper' => 'Počet řádků, které mají být importovány najednou, je definován velikostí bloku. Zvyšte tuto hodnotu, pokud máte velký soubor a data jsou importována velmi rychle. Snižte tuto hodnotu, pokud narazíte na omezení paměti nebo problémy s časovým limitem brány při importu dat.',
        ],

        'failures' => [
            'title' => 'Selhání',
            'attribute' => 'Atribut',
            'errors' => 'Chyby',
        ],

        'example' => [
            'title' => 'Příklad',
            'download' => 'Stáhnout příklad souboru :type',
        ],

        'rules' => [
            'title' => 'Pravidla',
            'column' => 'Sloupec',
        ],

        'uploading_message' => 'Zahajování nahrávání souboru...',
        'uploaded_message' => 'Soubor :file byl úspěšně nahrán. Zahájení ověřování dat...',
        'validating_message' => 'Ověřování od :from do :to...',
        'importing_message' => 'Importování od :from do :to...',
        'done_message' => 'Úspěšně importováno :count :label.',
        'validating_failed_message' => 'Ověření selhalo. Zkontrolujte prosím níže uvedené chyby.',
        'no_data_message' => 'Vaše data jsou již aktuální nebo nejsou k dispozici žádná data k importu.',
    ],

    'export' => [
        'name' => 'Export',
        'heading' => 'Export :label',
        'excel_not_supported_for_large_exports' => 'Formát Excel není podporován pro velké exporty (:count položek). Místo toho použijte formát CSV pro lepší výkon a spolehlivost.',

        'form' => [
            'all_columns_disabled' => 'Budou exportovány následující sloupce: :columns.',
            'columns' => 'Sloupce',
            'format' => 'Formát',
            'export_button' => 'Exportovat',
        ],

        'success_message' => 'Úspěšně exportováno.',
        'error_message' => 'Export selhal.',

        'empty_state' => [
            'title' => 'Žádná data k exportu',
            'description' => 'Zdá se, že nejsou k dispozici žádná data k exportu.',
            'back' => 'Zpět na :page',
        ],
    ],
    'check_all' => 'Vybrat vše',
];
