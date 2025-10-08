<?php

return [
    'tools' => [
        'export_import_data' => 'Izvozi/Uvozi podatke',
    ],

    'import' => [
        'name' => 'Uvozi',
        'heading' => 'Uvozi :label',
        'failed_to_read_file' => 'Datoteka je neveljavna, poškodovana ali prevelika za branje.',

        'form' => [
            'quick_export_message' => 'Če želite izvoziti podatke :label, lahko to hitro storite s klikom na :export_csv_link ali :export_excel_link.',
            'quick_export_button' => 'Izvozi v :format',
            'dropzone_message' => 'Povlecite in spustite datoteko sem ali kliknite za nalaganje',
            'allowed_extensions' => 'Izberite datoteko z naslednjimi končnicami: :extensions.',
            'import_button' => 'Uvozi',
            'chunk_size' => 'Velikost kosa',
            'chunk_size_helper' => 'Število vrstic, ki se uvozijo hkrati, je določeno z velikostjo kosa. Povečajte to vrednost, če imate veliko datoteko in se podatki uvozijo zelo hitro. Zmanjšajte to vrednost, če naletite na omejitve pomnilnika ali težave s časovno prekinitvijo prehoda pri uvozu podatkov.',
        ],

        'failures' => [
            'title' => 'Neuspehi',
            'attribute' => 'Atribut',
            'errors' => 'Napake',
        ],

        'example' => [
            'title' => 'Primer',
            'download' => 'Prenesite primer datoteke :type',
        ],

        'rules' => [
            'title' => 'Pravila',
            'column' => 'Stolpec',
        ],

        'uploading_message' => 'Začetek nalaganja datoteke...',
        'uploaded_message' => 'Datoteka :file je bila uspešno naložena. Začetek preverjanja podatkov...',
        'validating_message' => 'Preverjanje od :from do :to...',
        'importing_message' => 'Uvoz od :from do :to...',
        'done_message' => 'Uspešno uvoženih :count :label.',
        'validating_failed_message' => 'Preverjanje ni uspelo. Preverite napake spodaj.',
        'no_data_message' => 'Vaši podatki so že posodobljeni ali ni podatkov za uvoz.',
    ],

    'export' => [
        'name' => 'Izvozi',
        'heading' => 'Izvozi :label',
        'excel_not_supported_for_large_exports' => 'Format Excel ni podprt za velike izvoz (:count elementov). Uporabite format CSV za boljšo zmogljivost in zanesljivost.',

        'form' => [
            'all_columns_disabled' => 'Naslednji stolpci bodo izvoženi: :columns.',
            'columns' => 'Stolpci',
            'format' => 'Format',
            'export_button' => 'Izvozi',
        ],

        'success_message' => 'Uspešno izvoženo.',
        'error_message' => 'Izvoz ni uspel.',

        'empty_state' => [
            'title' => 'Ni podatkov za izvoz',
            'description' => 'Zdi se, da ni podatkov za izvoz.',
            'back' => 'Nazaj na :page',
        ],
    ],
    'check_all' => 'Izberi vse',
];
