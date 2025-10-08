<?php

return [
    'tools' => [
        'export_import_data' => 'Exportera/Importera data',
    ],

    'import' => [
        'name' => 'Importera',
        'heading' => 'Importera :label',
        'failed_to_read_file' => 'Filen är ogiltig, skadad eller för stor för att läsa.',

        'form' => [
            'quick_export_message' => 'Om du vill exportera :label data kan du snabbt göra det genom att klicka på :export_csv_link eller :export_excel_link.',
            'quick_export_button' => 'Exportera till :format',
            'dropzone_message' => 'Dra och släpp fil här eller klicka för att ladda upp',
            'allowed_extensions' => 'Välj en fil med följande tillägg: :extensions.',
            'import_button' => 'Importera',
            'chunk_size' => 'Chunk-storlek',
            'chunk_size_helper' => 'Antalet rader som ska importeras åt gången definieras av chunk-storleken. Öka detta värde om du har en stor fil och data importeras mycket snabbt. Minska detta värde om du stöter på minnesbegränsningar eller gateway timeout-problem vid import av data.',
        ],

        'failures' => [
            'title' => 'Misslyckanden',
            'attribute' => 'Attribut',
            'errors' => 'Fel',
        ],

        'example' => [
            'title' => 'Exempel',
            'download' => 'Ladda ner exempel :type fil',
        ],

        'rules' => [
            'title' => 'Regler',
            'column' => 'Kolumn',
        ],

        'uploading_message' => 'Börjar ladda upp fil...',
        'uploaded_message' => 'Filen :file har laddats upp framgångsrikt. Börjar validera data...',
        'validating_message' => 'Validerar från :from till :to...',
        'importing_message' => 'Importerar från :from till :to...',
        'done_message' => 'Importerade :count :label framgångsrikt.',
        'validating_failed_message' => 'Validering misslyckades. Kontrollera felen nedan.',
        'no_data_message' => 'Din data är redan uppdaterad eller det finns ingen data att importera.',
    ],

    'export' => [
        'name' => 'Exportera',
        'heading' => 'Exportera :label',
        'excel_not_supported_for_large_exports' => 'Excel-format stöds inte för stora exporter (:count objekt). Använd CSV-format istället för bättre prestanda och tillförlitlighet.',

        'form' => [
            'all_columns_disabled' => 'Följande kolumner kommer att exporteras: :columns.',
            'columns' => 'Kolumner',
            'format' => 'Format',
            'export_button' => 'Exportera',
        ],

        'success_message' => 'Exporterade framgångsrikt.',
        'error_message' => 'Export misslyckades.',

        'empty_state' => [
            'title' => 'Ingen data att exportera',
            'description' => 'Det verkar inte finnas någon data att exportera.',
            'back' => 'Tillbaka till :page',
        ],
    ],
    'check_all' => 'Markera alla',
];
