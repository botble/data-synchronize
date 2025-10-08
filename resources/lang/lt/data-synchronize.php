<?php

return [
    'tools' => [
        'export_import_data' => 'Eksportuoti/Importuoti duomenis',
    ],

    'import' => [
        'name' => 'Importuoti',
        'heading' => 'Importuoti :label',
        'failed_to_read_file' => 'Failas yra negaliojantis, sugadintas arba per didelis skaityti.',

        'form' => [
            'quick_export_message' => 'Jei norite eksportuoti :label duomenis, galite tai greitai padaryti spustelėję :export_csv_link arba :export_excel_link.',
            'quick_export_button' => 'Eksportuoti į :format',
            'dropzone_message' => 'Nuvilkite failą čia arba spustelėkite, kad įkeltumėte',
            'allowed_extensions' => 'Pasirinkite failą su šiais plėtiniais: :extensions.',
            'import_button' => 'Importuoti',
            'chunk_size' => 'Dalies dydis',
            'chunk_size_helper' => 'Vienu metu importuojamų eilučių skaičių nustato dalies dydis. Padidinkite šią reikšmę, jei turite didelį failą ir duomenys importuojami labai greitai. Sumažinkite šią reikšmę, jei importuojant duomenis susiduriate su atminties apribojimais arba šliuzo skirtojo laiko problemomis.',
        ],

        'failures' => [
            'title' => 'Nesėkmės',
            'attribute' => 'Atributas',
            'errors' => 'Klaidos',
        ],

        'example' => [
            'title' => 'Pavyzdys',
            'download' => 'Atsisiųsti pavyzdinį :type failą',
        ],

        'rules' => [
            'title' => 'Taisyklės',
            'column' => 'Stulpelis',
        ],

        'uploading_message' => 'Pradedamas failo įkėlimas...',
        'uploaded_message' => 'Failas :file sėkmingai įkeltas. Pradedamas duomenų tikrinimas...',
        'validating_message' => 'Tikrinimas nuo :from iki :to...',
        'importing_message' => 'Importavimas nuo :from iki :to...',
        'done_message' => 'Sėkmingai importuota :count :label.',
        'validating_failed_message' => 'Tikrinimas nepavyko. Patikrinkite klaidas žemiau.',
        'no_data_message' => 'Jūsų duomenys jau atnaujinti arba nėra duomenų importuoti.',
    ],

    'export' => [
        'name' => 'Eksportuoti',
        'heading' => 'Eksportuoti :label',
        'excel_not_supported_for_large_exports' => 'Excel formatas nepalaikomas dideliems eksportams (:count elementai). Geresniam našumui ir patikimumui naudokite CSV formatą.',

        'form' => [
            'all_columns_disabled' => 'Bus eksportuojami šie stulpeliai: :columns.',
            'columns' => 'Stulpeliai',
            'format' => 'Formatas',
            'export_button' => 'Eksportuoti',
        ],

        'success_message' => 'Sėkmingai eksportuota.',
        'error_message' => 'Eksportavimas nepavyko.',

        'empty_state' => [
            'title' => 'Nėra duomenų eksportavimui',
            'description' => 'Atrodo, kad nėra duomenų eksportavimui.',
            'back' => 'Grįžti į :page',
        ],
    ],
    'check_all' => 'Pažymėti viską',
];
