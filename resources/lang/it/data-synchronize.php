<?php

return [
    'tools' => [
        'export_import_data' => 'Esporta/Importa dati',
    ],

    'import' => [
        'name' => 'Importa',
        'heading' => 'Importa :label',
        'failed_to_read_file' => 'Il file non è valido, è corrotto o è troppo grande per essere letto.',

        'form' => [
            'quick_export_message' => 'Se vuoi esportare i dati :label, puoi farlo rapidamente facendo clic su :export_csv_link o :export_excel_link.',
            'quick_export_button' => 'Esporta in :format',
            'dropzone_message' => 'Trascina e rilascia il file qui o clicca per caricare',
            'allowed_extensions' => 'Scegli un file con le seguenti estensioni: :extensions.',
            'import_button' => 'Importa',
            'chunk_size' => 'Dimensione del blocco',
            'chunk_size_helper' => 'Il numero di righe da importare alla volta è definito dalla dimensione del blocco. Aumenta questo valore se hai un file grande e i dati vengono importati molto velocemente. Diminuisci questo valore se riscontri limiti di memoria o problemi di timeout del gateway durante l\'importazione dei dati.',
        ],

        'failures' => [
            'title' => 'Errori',
            'attribute' => 'Attributo',
            'errors' => 'Errori',
        ],

        'example' => [
            'title' => 'Esempio',
            'download' => 'Scarica file :type di esempio',
        ],

        'rules' => [
            'title' => 'Regole',
            'column' => 'Colonna',
        ],

        'uploading_message' => 'Inizio caricamento file...',
        'uploaded_message' => 'Il file :file è stato caricato con successo. Inizio validazione dati...',
        'validating_message' => 'Validazione da :from a :to...',
        'importing_message' => 'Importazione da :from a :to...',
        'done_message' => 'Importati con successo :count :label.',
        'validating_failed_message' => 'Validazione fallita. Controlla gli errori qui sotto.',
        'no_data_message' => 'I tuoi dati sono già aggiornati o non ci sono dati da importare.',
    ],

    'export' => [
        'name' => 'Esporta',
        'heading' => 'Esporta :label',
        'excel_not_supported_for_large_exports' => 'Il formato Excel non è supportato per esportazioni di grandi dimensioni (:count elementi). Si prega di utilizzare il formato CSV per migliori prestazioni e affidabilità.',

        'form' => [
            'all_columns_disabled' => 'Le seguenti colonne verranno esportate: :columns.',
            'columns' => 'Colonne',
            'format' => 'Formato',
            'export_button' => 'Esporta',
        ],

        'success_message' => 'Esportato con successo.',
        'error_message' => 'Esportazione fallita.',

        'empty_state' => [
            'title' => 'Nessun dato da esportare',
            'description' => 'Sembra che non ci siano dati da esportare.',
            'back' => 'Torna a :page',
        ],
    ],
    'check_all' => 'Seleziona tutto',
];
