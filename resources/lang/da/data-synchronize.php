<?php

return [
    'tools' => [
        'export_import_data' => 'Eksporter/Importer data',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Importer :label',
        'failed_to_read_file' => 'Filen er ugyldig, beskadiget eller for stor til at læse.',

        'form' => [
            'quick_export_message' => 'Hvis du vil eksportere :label data, kan du hurtigt gøre det ved at klikke på :export_csv_link eller :export_excel_link.',
            'quick_export_button' => 'Eksporter til :format',
            'dropzone_message' => 'Træk og slip fil her eller klik for at uploade',
            'allowed_extensions' => 'Vælg en fil med følgende udvidelser: :extensions.',
            'import_button' => 'Importer',
            'chunk_size' => 'Chunk størrelse',
            'chunk_size_helper' => 'Antallet af rækker, der skal importeres på én gang, defineres af chunk størrelsen. Øg denne værdi, hvis du har en stor fil, og data importeres meget hurtigt. Reducer denne værdi, hvis du støder på hukommelsesbegrænsninger eller gateway timeout problemer ved import af data.',
        ],

        'failures' => [
            'title' => 'Fejl',
            'attribute' => 'Attribut',
            'errors' => 'Fejl',
        ],

        'example' => [
            'title' => 'Eksempel',
            'download' => 'Download eksempel :type fil',
        ],

        'rules' => [
            'title' => 'Regler',
            'column' => 'Kolonne',
        ],

        'uploading_message' => 'Starter upload af fil...',
        'uploaded_message' => 'Filen :file er blevet uploadet med succes. Start validering af data...',
        'validating_message' => 'Validerer fra :from til :to...',
        'importing_message' => 'Importerer fra :from til :to...',
        'done_message' => 'Importerede :count :label med succes.',
        'validating_failed_message' => 'Validering mislykkedes. Tjek venligst fejlene nedenfor.',
        'no_data_message' => 'Dine data er allerede opdateret, eller der er ingen data at importere.',
    ],

    'export' => [
        'name' => 'Eksport',
        'heading' => 'Eksporter :label',
        'excel_not_supported_for_large_exports' => 'Excel-format understøttes ikke til store eksporter (:count elementer). Brug venligst CSV-format i stedet for bedre ydeevne og pålidelighed.',

        'form' => [
            'all_columns_disabled' => 'Følgende kolonner vil blive eksporteret: :columns.',
            'columns' => 'Kolonner',
            'format' => 'Format',
            'export_button' => 'Eksporter',
        ],

        'success_message' => 'Eksporteret med succes.',
        'error_message' => 'Eksport mislykkedes.',

        'empty_state' => [
            'title' => 'Ingen data at eksportere',
            'description' => 'Det ser ud til, at der ikke er nogen data at eksportere.',
            'back' => 'Tilbage til :page',
        ],
    ],
    'check_all' => 'Vælg alle',
];
