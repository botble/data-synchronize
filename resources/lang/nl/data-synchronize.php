<?php

return [
    'tools' => [
        'export_import_data' => 'Gegevens exporteren/importeren',
    ],

    'import' => [
        'name' => 'Importeren',
        'heading' => ':label importeren',
        'failed_to_read_file' => 'Het bestand is ongeldig, beschadigd of te groot om te lezen.',

        'form' => [
            'quick_export_message' => 'Als u :label gegevens wilt exporteren, kunt u dit snel doen door te klikken op :export_csv_link of :export_excel_link.',
            'quick_export_button' => 'Exporteren naar :format',
            'dropzone_message' => 'Sleep het bestand hierheen of klik om te uploaden',
            'allowed_extensions' => 'Kies een bestand met de volgende extensies: :extensions.',
            'import_button' => 'Importeren',
            'chunk_size' => 'Chunkgrootte',
            'chunk_size_helper' => 'Het aantal rijen dat tegelijk wordt geïmporteerd, wordt gedefinieerd door de chunkgrootte. Verhoog deze waarde als u een groot bestand hebt en gegevens zeer snel worden geïmporteerd. Verlaag deze waarde als u geheugenlimieten of gateway time-out problemen tegenkomt bij het importeren van gegevens.',
        ],

        'failures' => [
            'title' => 'Mislukkingen',
            'attribute' => 'Attribuut',
            'errors' => 'Fouten',
        ],

        'example' => [
            'title' => 'Voorbeeld',
            'download' => 'Download voorbeeld :type bestand',
        ],

        'rules' => [
            'title' => 'Regels',
            'column' => 'Kolom',
        ],

        'uploading_message' => 'Bestand uploaden gestart...',
        'uploaded_message' => 'Bestand :file is succesvol geüpload. Gegevensvalidatie starten...',
        'validating_message' => 'Valideren van :from tot :to...',
        'importing_message' => 'Importeren van :from tot :to...',
        'done_message' => 'Succesvol :count :label geïmporteerd.',
        'validating_failed_message' => 'Validatie mislukt. Controleer de onderstaande fouten.',
        'no_data_message' => 'Uw gegevens zijn al up-to-date of er zijn geen gegevens om te importeren.',
    ],

    'export' => [
        'name' => 'Exporteren',
        'heading' => ':label exporteren',
        'excel_not_supported_for_large_exports' => 'Excel-formaat wordt niet ondersteund voor grote exports (:count items). Gebruik alstublieft het CSV-formaat voor betere prestaties en betrouwbaarheid.',

        'form' => [
            'all_columns_disabled' => 'De volgende kolommen worden geëxporteerd: :columns.',
            'columns' => 'Kolommen',
            'format' => 'Formaat',
            'export_button' => 'Exporteren',
        ],

        'success_message' => 'Succesvol geëxporteerd.',
        'error_message' => 'Exporteren mislukt.',

        'empty_state' => [
            'title' => 'Geen gegevens om te exporteren',
            'description' => 'Het lijkt erop dat er geen gegevens zijn om te exporteren.',
            'back' => 'Terug naar :page',
        ],
    ],
    'check_all' => 'Alles selecteren',
];
