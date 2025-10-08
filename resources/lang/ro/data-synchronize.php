<?php

return [
    'tools' => [
        'export_import_data' => 'Exportă/Importă date',
    ],

    'import' => [
        'name' => 'Importă',
        'heading' => 'Importă :label',
        'failed_to_read_file' => 'Fișierul este invalid, corupt sau prea mare pentru a fi citit.',

        'form' => [
            'quick_export_message' => 'Dacă doriți să exportați date :label, puteți face acest lucru rapid făcând clic pe :export_csv_link sau :export_excel_link.',
            'quick_export_button' => 'Exportă în :format',
            'dropzone_message' => 'Trageți și plasați fișierul aici sau faceți clic pentru a încărca',
            'allowed_extensions' => 'Alegeți un fișier cu următoarele extensii: :extensions.',
            'import_button' => 'Importă',
            'chunk_size' => 'Dimensiunea fragmentului',
            'chunk_size_helper' => 'Numărul de rânduri care urmează să fie importate dintr-o dată este definit de dimensiunea fragmentului. Măriți această valoare dacă aveți un fișier mare și datele sunt importate foarte rapid. Micșorați această valoare dacă întâmpinați limite de memorie sau probleme de timeout la gateway la importul datelor.',
        ],

        'failures' => [
            'title' => 'Eșecuri',
            'attribute' => 'Atribut',
            'errors' => 'Erori',
        ],

        'example' => [
            'title' => 'Exemplu',
            'download' => 'Descarcă fișier :type exemplu',
        ],

        'rules' => [
            'title' => 'Reguli',
            'column' => 'Coloană',
        ],

        'uploading_message' => 'Se începe încărcarea fișierului...',
        'uploaded_message' => 'Fișierul :file a fost încărcat cu succes. Se începe validarea datelor...',
        'validating_message' => 'Se validează de la :from la :to...',
        'importing_message' => 'Se importă de la :from la :to...',
        'done_message' => 'S-au importat cu succes :count :label.',
        'validating_failed_message' => 'Validarea a eșuat. Verificați erorile de mai jos.',
        'no_data_message' => 'Datele dvs. sunt deja actualizate sau nu există date de importat.',
    ],

    'export' => [
        'name' => 'Exportă',
        'heading' => 'Exportă :label',
        'excel_not_supported_for_large_exports' => 'Formatul Excel nu este acceptat pentru exporturi mari (:count elemente). Vă rugăm să utilizați formatul CSV pentru performanță și fiabilitate mai bune.',

        'form' => [
            'all_columns_disabled' => 'Următoarele coloane vor fi exportate: :columns.',
            'columns' => 'Coloane',
            'format' => 'Format',
            'export_button' => 'Exportă',
        ],

        'success_message' => 'Exportat cu succes.',
        'error_message' => 'Exportul a eșuat.',

        'empty_state' => [
            'title' => 'Nu există date de exportat',
            'description' => 'Se pare că nu există date de exportat.',
            'back' => 'Înapoi la :page',
        ],
    ],
    'check_all' => 'Selectează tot',
];
