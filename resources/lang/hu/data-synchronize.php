<?php

return [
    'tools' => [
        'export_import_data' => 'Adatok exportálása/importálása',
    ],

    'import' => [
        'name' => 'Importálás',
        'heading' => ':label importálása',
        'failed_to_read_file' => 'A fájl érvénytelen, sérült vagy túl nagy az olvasáshoz.',

        'form' => [
            'quick_export_message' => 'Ha exportálni szeretné a :label adatokat, gyorsan megteheti a :export_csv_link vagy :export_excel_link linkre kattintva.',
            'quick_export_button' => 'Exportálás :format formátumba',
            'dropzone_message' => 'Húzza ide a fájlt, vagy kattintson a feltöltéshez',
            'allowed_extensions' => 'Válasszon fájlt a következő kiterjesztésekkel: :extensions.',
            'import_button' => 'Importálás',
            'chunk_size' => 'Darab méret',
            'chunk_size_helper' => 'Az egyszerre importálandó sorok számát a darab méret határozza meg. Növelje ezt az értéket, ha nagy fájlja van, és az adatok nagyon gyorsan importálódnak. Csökkentse ezt az értéket, ha memória korlátozásokba vagy átjáró időtúllépési problémákba ütközik adatok importálásakor.',
        ],

        'failures' => [
            'title' => 'Hibák',
            'attribute' => 'Attribútum',
            'errors' => 'Hibák',
        ],

        'example' => [
            'title' => 'Példa',
            'download' => 'Példa :type fájl letöltése',
        ],

        'rules' => [
            'title' => 'Szabályok',
            'column' => 'Oszlop',
        ],

        'uploading_message' => 'Fájl feltöltése kezdődik...',
        'uploaded_message' => 'A :file fájl sikeresen feltöltve. Adatok érvényesítése kezdődik...',
        'validating_message' => 'Érvényesítés :from-tól :to-ig...',
        'importing_message' => 'Importálás :from-tól :to-ig...',
        'done_message' => 'Sikeresen importálva :count :label.',
        'validating_failed_message' => 'Az érvényesítés sikertelen. Kérjük, ellenőrizze az alábbi hibákat.',
        'no_data_message' => 'Az adatai már naprakészek, vagy nincsenek importálandó adatok.',
    ],

    'export' => [
        'name' => 'Exportálás',
        'heading' => ':label exportálása',
        'excel_not_supported_for_large_exports' => 'Az Excel formátum nem támogatott nagy exportokhoz (:count elem). Kérjük, használjon CSV formátumot a jobb teljesítmény és megbízhatóság érdekében.',

        'form' => [
            'all_columns_disabled' => 'A következő oszlopok lesznek exportálva: :columns.',
            'columns' => 'Oszlopok',
            'format' => 'Formátum',
            'export_button' => 'Exportálás',
        ],

        'success_message' => 'Sikeresen exportálva.',
        'error_message' => 'Az exportálás sikertelen.',

        'empty_state' => [
            'title' => 'Nincs exportálandó adat',
            'description' => 'Úgy tűnik, nincs exportálandó adat.',
            'back' => 'Vissza a :page oldalra',
        ],
    ],
    'check_all' => 'Összes kijelölése',
];
