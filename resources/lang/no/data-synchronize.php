<?php

return [
    'tools' => [
        'export_import_data' => 'Eksporter/Importer data',
    ],

    'import' => [
        'name' => 'Importer',
        'heading' => 'Importer :label',
        'failed_to_read_file' => 'Filen er ugyldig, skadet eller for stor til å lese.',

        'form' => [
            'quick_export_message' => 'Hvis du vil eksportere :label data, kan du gjøre det raskt ved å klikke på :export_csv_link eller :export_excel_link.',
            'quick_export_button' => 'Eksporter til :format',
            'dropzone_message' => 'Dra og slipp fil her eller klikk for å laste opp',
            'allowed_extensions' => 'Velg en fil med følgende utvidelser: :extensions.',
            'import_button' => 'Importer',
            'chunk_size' => 'Chunk-størrelse',
            'chunk_size_helper' => 'Antall rader som skal importeres om gangen defineres av chunk-størrelsen. Øk denne verdien hvis du har en stor fil og data importeres veldig raskt. Reduser denne verdien hvis du opplever minnebegrensninger eller gateway timeout-problemer ved import av data.',
        ],

        'failures' => [
            'title' => 'Feil',
            'attribute' => 'Attributt',
            'errors' => 'Feil',
        ],

        'example' => [
            'title' => 'Eksempel',
            'download' => 'Last ned eksempel :type fil',
        ],

        'rules' => [
            'title' => 'Regler',
            'column' => 'Kolonne',
        ],

        'uploading_message' => 'Starter opplasting av fil...',
        'uploaded_message' => 'Filen :file har blitt lastet opp. Starter validering av data...',
        'validating_message' => 'Validerer fra :from til :to...',
        'importing_message' => 'Importerer fra :from til :to...',
        'done_message' => 'Importerte :count :label vellykket.',
        'validating_failed_message' => 'Validering mislyktes. Vennligst sjekk feilene nedenfor.',
        'no_data_message' => 'Dataene dine er allerede oppdatert eller det er ingen data å importere.',
    ],

    'export' => [
        'name' => 'Eksporter',
        'heading' => 'Eksporter :label',
        'excel_not_supported_for_large_exports' => 'Excel-format støttes ikke for store eksporter (:count elementer). Vennligst bruk CSV-format i stedet for bedre ytelse og pålitelighet.',

        'form' => [
            'all_columns_disabled' => 'Følgende kolonner vil bli eksportert: :columns.',
            'columns' => 'Kolonner',
            'format' => 'Format',
            'export_button' => 'Eksporter',
        ],

        'success_message' => 'Eksportert vellykket.',
        'error_message' => 'Eksport mislyktes.',

        'empty_state' => [
            'title' => 'Ingen data å eksportere',
            'description' => 'Det ser ut til at det ikke er noen data å eksportere.',
            'back' => 'Tilbake til :page',
        ],
    ],
    'check_all' => 'Velg alle',
];
