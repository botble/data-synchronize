<?php

return [
    'tools' => [
        'export_import_data' => 'Eksport/Import Data',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Import :label',
        'failed_to_read_file' => 'Fail tidak sah, rosak atau terlalu besar untuk dibaca.',

        'form' => [
            'quick_export_message' => 'Jika anda ingin mengeksport data :label, anda boleh melakukannya dengan cepat dengan mengklik pada :export_csv_link atau :export_excel_link.',
            'quick_export_button' => 'Eksport ke :format',
            'dropzone_message' => 'Seret dan lepaskan fail di sini atau klik untuk muat naik',
            'allowed_extensions' => 'Pilih fail dengan sambungan berikut: :extensions.',
            'import_button' => 'Import',
            'chunk_size' => 'Saiz cebisan',
            'chunk_size_helper' => 'Bilangan baris yang akan diimport pada satu masa ditakrifkan oleh saiz cebisan. Tingkatkan nilai ini jika anda mempunyai fail yang besar dan data diimport dengan sangat cepat. Kurangkan nilai ini jika anda menghadapi had memori atau masalah tamat masa gerbang semasa mengimport data.',
        ],

        'failures' => [
            'title' => 'Kegagalan',
            'attribute' => 'Atribut',
            'errors' => 'Ralat',
        ],

        'example' => [
            'title' => 'Contoh',
            'download' => 'Muat turun contoh fail :type',
        ],

        'rules' => [
            'title' => 'Peraturan',
            'column' => 'Lajur',
        ],

        'uploading_message' => 'Mula memuat naik fail...',
        'uploaded_message' => 'Fail :file telah berjaya dimuat naik. Mula mengesahkan data...',
        'validating_message' => 'Mengesahkan dari :from ke :to...',
        'importing_message' => 'Mengimport dari :from ke :to...',
        'done_message' => 'Berjaya mengimport :count :label.',
        'validating_failed_message' => 'Pengesahan gagal. Sila semak ralat di bawah.',
        'no_data_message' => 'Data anda sudah terkini atau tiada data untuk diimport.',
    ],

    'export' => [
        'name' => 'Eksport',
        'heading' => 'Eksport :label',
        'excel_not_supported_for_large_exports' => 'Format Excel tidak disokong untuk eksport besar (:count item). Sila gunakan format CSV untuk prestasi dan kebolehpercayaan yang lebih baik.',

        'form' => [
            'all_columns_disabled' => 'Lajur berikut akan dieksport: :columns.',
            'columns' => 'Lajur',
            'format' => 'Format',
            'export_button' => 'Eksport',
        ],

        'success_message' => 'Berjaya dieksport.',
        'error_message' => 'Eksport gagal.',

        'empty_state' => [
            'title' => 'Tiada data untuk dieksport',
            'description' => 'Nampaknya tiada data untuk dieksport.',
            'back' => 'Kembali ke :page',
        ],
    ],
    'check_all' => 'Tandakan semua',
];
