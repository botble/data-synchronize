<?php

return [
    'tools' => [
        'export_import_data' => 'Eksportuj/Importuj dane',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Importuj :label',
        'failed_to_read_file' => 'Plik jest nieprawidłowy, uszkodzony lub zbyt duży do odczytu.',

        'form' => [
            'quick_export_message' => 'Jeśli chcesz wyeksportować dane :label, możesz to szybko zrobić, klikając na :export_csv_link lub :export_excel_link.',
            'quick_export_button' => 'Eksportuj do :format',
            'dropzone_message' => 'Przeciągnij i upuść plik tutaj lub kliknij, aby przesłać',
            'allowed_extensions' => 'Wybierz plik z następującymi rozszerzeniami: :extensions.',
            'import_button' => 'Importuj',
            'chunk_size' => 'Rozmiar fragmentu',
            'chunk_size_helper' => 'Liczba wierszy do zaimportowania na raz jest określana przez rozmiar fragmentu. Zwiększ tę wartość, jeśli masz duży plik, a dane są importowane bardzo szybko. Zmniejsz tę wartość, jeśli napotkasz ograniczenia pamięci lub problemy z przekroczeniem limitu czasu bramy podczas importowania danych.',
        ],

        'failures' => [
            'title' => 'Błędy',
            'attribute' => 'Atrybut',
            'errors' => 'Błędy',
        ],

        'example' => [
            'title' => 'Przykład',
            'download' => 'Pobierz przykładowy plik :type',
        ],

        'rules' => [
            'title' => 'Zasady',
            'column' => 'Kolumna',
        ],

        'uploading_message' => 'Rozpoczynanie przesyłania pliku...',
        'uploaded_message' => 'Plik :file został pomyślnie przesłany. Rozpoczynanie walidacji danych...',
        'validating_message' => 'Walidacja od :from do :to...',
        'importing_message' => 'Importowanie od :from do :to...',
        'done_message' => 'Pomyślnie zaimportowano :count :label.',
        'validating_failed_message' => 'Walidacja nie powiodła się. Sprawdź błędy poniżej.',
        'no_data_message' => 'Twoje dane są już aktualne lub nie ma danych do importu.',
    ],

    'export' => [
        'name' => 'Eksport',
        'heading' => 'Eksportuj :label',
        'excel_not_supported_for_large_exports' => 'Format Excel nie jest obsługiwany dla dużych eksportów (:count elementów). Użyj formatu CSV dla lepszej wydajności i niezawodności.',

        'form' => [
            'all_columns_disabled' => 'Następujące kolumny zostaną wyeksportowane: :columns.',
            'columns' => 'Kolumny',
            'format' => 'Format',
            'export_button' => 'Eksportuj',
        ],

        'success_message' => 'Pomyślnie wyeksportowano.',
        'error_message' => 'Eksport nie powiódł się.',

        'empty_state' => [
            'title' => 'Brak danych do eksportu',
            'description' => 'Wygląda na to, że nie ma danych do eksportu.',
            'back' => 'Powrót do :page',
        ],
    ],
    'check_all' => 'Zaznacz wszystko',
];
