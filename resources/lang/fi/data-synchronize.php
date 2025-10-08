<?php

return [
    'tools' => [
        'export_import_data' => 'Vie/Tuo tietoja',
    ],

    'import' => [
        'name' => 'Tuonti',
        'heading' => 'Tuo :label',
        'failed_to_read_file' => 'Tiedosto on virheellinen, vioittunut tai liian suuri luettavaksi.',

        'form' => [
            'quick_export_message' => 'Jos haluat viedä :label tiedot, voit tehdä sen nopeasti napsauttamalla :export_csv_link tai :export_excel_link.',
            'quick_export_button' => 'Vie muodossa :format',
            'dropzone_message' => 'Vedä ja pudota tiedosto tähän tai napsauta ladataksesi',
            'allowed_extensions' => 'Valitse tiedosto seuraavilla tunnisteilla: :extensions.',
            'import_button' => 'Tuo',
            'chunk_size' => 'Palan koko',
            'chunk_size_helper' => 'Kerralla tuotavien rivien määrä määritellään palan koolla. Suurenna tätä arvoa, jos sinulla on suuri tiedosto ja tiedot tuodaan erittäin nopeasti. Pienennä tätä arvoa, jos kohtaat muistin rajoituksia tai yhdyskäytävän aikakatkaisuongelmia tietojen tuonnissa.',
        ],

        'failures' => [
            'title' => 'Epäonnistumiset',
            'attribute' => 'Attribuutti',
            'errors' => 'Virheet',
        ],

        'example' => [
            'title' => 'Esimerkki',
            'download' => 'Lataa esimerkki :type tiedosto',
        ],

        'rules' => [
            'title' => 'Säännöt',
            'column' => 'Sarake',
        ],

        'uploading_message' => 'Aloitetaan tiedoston lataamista...',
        'uploaded_message' => 'Tiedosto :file on ladattu onnistuneesti. Aloitetaan tietojen validointi...',
        'validating_message' => 'Validoidaan kohteesta :from kohteeseen :to...',
        'importing_message' => 'Tuodaan kohteesta :from kohteeseen :to...',
        'done_message' => 'Tuotu onnistuneesti :count :label.',
        'validating_failed_message' => 'Validointi epäonnistui. Tarkista alla olevat virheet.',
        'no_data_message' => 'Tietosi ovat jo ajan tasalla tai tuotavaa dataa ei ole.',
    ],

    'export' => [
        'name' => 'Vienti',
        'heading' => 'Vie :label',
        'excel_not_supported_for_large_exports' => 'Excel-muotoa ei tueta suurissa vienneissä (:count kohdetta). Käytä sen sijaan CSV-muotoa paremman suorituskyvyn ja luotettavuuden vuoksi.',

        'form' => [
            'all_columns_disabled' => 'Seuraavat sarakkeet viedään: :columns.',
            'columns' => 'Sarakkeet',
            'format' => 'Muoto',
            'export_button' => 'Vie',
        ],

        'success_message' => 'Viety onnistuneesti.',
        'error_message' => 'Vienti epäonnistui.',

        'empty_state' => [
            'title' => 'Ei vietävää dataa',
            'description' => 'Näyttää siltä, ettei vietävää dataa ole.',
            'back' => 'Takaisin sivulle :page',
        ],
    ],
    'check_all' => 'Valitse kaikki',
];
