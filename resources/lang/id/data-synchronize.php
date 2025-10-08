<?php

return [
    'tools' => [
        'export_import_data' => 'Ekspor/Impor Data',
    ],

    'import' => [
        'name' => 'Impor',
        'heading' => 'Impor :label',
        'failed_to_read_file' => 'File tidak valid, rusak atau terlalu besar untuk dibaca.',

        'form' => [
            'quick_export_message' => 'Jika Anda ingin mengekspor data :label, Anda dapat melakukannya dengan cepat dengan mengklik :export_csv_link atau :export_excel_link.',
            'quick_export_button' => 'Ekspor ke :format',
            'dropzone_message' => 'Seret dan lepas file di sini atau klik untuk mengunggah',
            'allowed_extensions' => 'Pilih file dengan ekstensi berikut: :extensions.',
            'import_button' => 'Impor',
            'chunk_size' => 'Ukuran chunk',
            'chunk_size_helper' => 'Jumlah baris yang akan diimpor dalam satu waktu ditentukan oleh ukuran chunk. Tingkatkan nilai ini jika Anda memiliki file besar dan data diimpor sangat cepat. Kurangi nilai ini jika Anda mengalami batasan memori atau masalah timeout gateway saat mengimpor data.',
        ],

        'failures' => [
            'title' => 'Kegagalan',
            'attribute' => 'Atribut',
            'errors' => 'Kesalahan',
        ],

        'example' => [
            'title' => 'Contoh',
            'download' => 'Unduh contoh file :type',
        ],

        'rules' => [
            'title' => 'Aturan',
            'column' => 'Kolom',
        ],

        'uploading_message' => 'Mulai mengunggah file...',
        'uploaded_message' => 'File :file telah berhasil diunggah. Mulai memvalidasi data...',
        'validating_message' => 'Memvalidasi dari :from ke :to...',
        'importing_message' => 'Mengimpor dari :from ke :to...',
        'done_message' => 'Berhasil mengimpor :count :label.',
        'validating_failed_message' => 'Validasi gagal. Silakan periksa kesalahan di bawah ini.',
        'no_data_message' => 'Data Anda sudah terbaru atau tidak ada data untuk diimpor.',
    ],

    'export' => [
        'name' => 'Ekspor',
        'heading' => 'Ekspor :label',
        'excel_not_supported_for_large_exports' => 'Format Excel tidak didukung untuk ekspor besar (:count item). Silakan gunakan format CSV untuk performa dan keandalan yang lebih baik.',

        'form' => [
            'all_columns_disabled' => 'Kolom berikut akan diekspor: :columns.',
            'columns' => 'Kolom',
            'format' => 'Format',
            'export_button' => 'Ekspor',
        ],

        'success_message' => 'Berhasil diekspor.',
        'error_message' => 'Ekspor gagal.',

        'empty_state' => [
            'title' => 'Tidak ada data untuk diekspor',
            'description' => 'Sepertinya tidak ada data untuk diekspor.',
            'back' => 'Kembali ke :page',
        ],
    ],
    'check_all' => 'Centang semua',
];
