<?php

return [
    'tools' => [
        'export_import_data' => 'Veriyi Dışa Aktar/İçe Aktar',
    ],
    'import' => [
        'name' => 'İçe Aktar',
        'heading' => 'İçe Aktar :label',
        'failed_to_read_file' => 'Dosya geçersiz veya bozuk veya okunamayacak kadar büyük.',
        'form' => [
            'quick_export_message' => ':label verilerini dışarı aktarmak istiyorsanız, :export_csv_link veya :export_excel_link\'e tıklayarak bunu hızlı bir şekilde yapabilirsiniz.',
            'quick_export_button' => ':format biçiminde dışa aktar',
            'dropzone_message' => 'Dosyayı buraya sürükleyip bırakın veya yüklemek için tıklayın',
            'allowed_extensions' => 'Aşağıdaki uzantılara sahip bir dosya seçin: :extensions.',
            'import_button' => 'İçe Aktar',
            'chunk_size' => 'Parça boyutu',
            'chunk_size_helper' => 'Bir seferde içe aktarılacak satır sayısı, parça boyutuyla tanımlanır. Büyük bir dosyanız varsa ve veriler çok hızlı içe aktarılıyorsa bu değeri artırın. Verileri içe aktarırken bellek sınırlamaları veya ağ geçidi zaman aşımı sorunlarıyla karşılaşırsanız bu değeri azaltın.',
        ],
        'failures' => [
            'title' => 'Başarısızlıklar',
            'attribute' => 'Bağlanmak',
            'errors' => 'Hatalar',
        ],
        'example' => [
            'title' => 'Örnek',
            'download' => 'Örnek indir :type file',
        ],
        'rules' => [
            'title' => 'Tüzük',
            'column' => 'Kolon',
        ],
        'uploading_message' => 'Dosya yüklenmeye başlıyor...',
        'uploaded_message' => 'Dosya :file başarıyla yüklendi. Verileri doğrulamaya başla...',
        'validating_message' => ':from to :to... doğrulanıyor.',
        'importing_message' => ':from\'dan :to \'ya aktarılıyor...',
        'done_message' => ':count :label başarıyla içe aktarıldı.',
        'validating_failed_message' => 'Doğrulama başarısız oldu. Lütfen aşağıdaki hataları kontrol edin.',
        'no_data_message' => 'Verileriniz zaten güncel veya aktarılacak veri yok.',
    ],
    'export' => [
        'name' => 'Dışa Aktar',
        'heading' => 'Dışa Aktar :label',
        'excel_not_supported_for_large_exports' => 'Excel formatı büyük dışa aktarmalar için desteklenmemektedir (:count öğe). Daha iyi performans ve güvenilirlik için lütfen CSV formatını kullanın.',
        'form' => [
            'all_columns_disabled' => 'Aşağıdaki sütunlar dışa aktarılacak: :columns.',
            'columns' => 'Sütunlar',
            'format' => 'Biçim',
            'export_button' => 'Dışa Aktar',
        ],
        'success_message' => 'Başarıyla dışa aktarıldı.',
        'error_message' => 'Dışa aktarma başarısız oldu.',
        'empty_state' => [
            'title' => 'Dışa aktarılacak veri yok',
            'description' => 'Görünüşe göre dışa aktarılacak veri yok.',
            'back' => 'Geri dön :page',
        ],
    ],
    'check_all' => 'Tümünü seç',
];
