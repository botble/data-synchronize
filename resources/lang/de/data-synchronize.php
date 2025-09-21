<?php

return [
    'tools' => [
        'export_import_data' => 'Daten exportieren/importieren',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => ':label importieren',
        'failed_to_read_file' => 'Die Datei ist ungültig oder beschädigt oder zu groß zum Lesen.',

        'form' => [
            'quick_export_message' => 'Wenn Sie :label Daten exportieren möchten, können Sie dies schnell tun, indem Sie auf :export_csv_link oder :export_excel_link klicken.',
            'quick_export_button' => 'Exportieren nach :format',
            'dropzone_message' => 'Ziehen Sie die Datei hier oder klicken Sie hier, um hochzuladen',
            'allowed_extensions' => 'Wählen Sie eine Datei mit folgenden Erweiterungen: :extensions.',
            'import_button' => 'Importieren',
            'chunk_size' => 'Chunk-Größe',
            'chunk_size_helper' => 'Die Anzahl der Zeilen, die jeweils importiert werden sollen, wird durch die Chunk-Größe definiert. Erhöhen Sie diesen Wert, wenn Sie eine große Datei haben und die Daten sehr schnell importiert werden. Verringern Sie diesen Wert, wenn Sie beim Importieren von Speicherlimits oder Timeout-Problemen mit Gateway-Timeout stoßen.',
        ],

        'failures' => [
            'title' => 'Fehler',
            'attribute' => 'Attribut',
            'errors' => 'Fehler',
        ],

        'example' => [
            'title' => 'Beispiel',
            'download' => 'Beispiel :type Datei herunterladen',
        ],

        'rules' => [
            'title' => 'Regeln',
            'column' => 'Spalte',
        ],

        'uploading_message' => 'Datei hochladen ...',
        'uploaded_message' => 'Datei :file wurde erfolgreich hochgeladen. Beginne Datenvalidierung...',
        'validating_message' => 'Validierung von :from bis :to...',
        'importing_message' => 'Importiere von :from bis :to...',
        'done_message' => ':count :label erfolgreich importiert.',
        'validating_failed_message' => 'Validierung fehlgeschlagen. Bitte überprüfen Sie die folgenden Fehler.',
        'no_data_message' => 'Ihre Daten sind bereits auf dem neuesten Stand oder keine Daten zum Importieren.',
    ],

    'export' => [
        'name' => 'Export',
        'heading' => ':label exportieren',
        'excel_not_supported_for_large_exports' => 'Excel-Format wird für große Exporte (:count Elemente) nicht unterstützt. Bitte verwenden Sie das CSV-Format für bessere Leistung und Zuverlässigkeit.',

        'form' => [
            'all_columns_disabled' => 'Folgende Spalten werden exportiert: :columns.',
            'columns' => 'Spalten',
            'format' => 'Format',
            'export_button' => 'Exportieren',
        ],

        'success_message' => 'Erfolgreich exportiert.',
        'error_message' => 'Export fehlgeschlagen.',

        'empty_state' => [
            'title' => 'Keine Daten zum Exportieren',
            'description' => 'Sieht so aus, als ob es keine Daten zum Exportieren gibt.',
            'back' => 'Zurück zu :page',
        ],
    ],
    'check_all' => 'Alle auswählen',
];