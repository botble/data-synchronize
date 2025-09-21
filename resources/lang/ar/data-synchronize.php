<?php

return [
    'tools' => [
        'export_import_data' => 'تصدير/استيراد البيانات',
    ],

    'import' => [
        'name' => 'استيراد',
        'heading' => 'استيراد :label',
        'failed_to_read_file' => 'الملف غير صالح أو تالف أو كبير جدًا بحيث لا يمكن قراءته.',

        'form' => [
            'quick_export_message' => 'إذا كنت تريد تصدير بيانات :label، يمكنك القيام بذلك بسرعة بالنقر فوق :export_csv_link أو :export_excel_link.',
            'quick_export_button' => 'تصدير إلى :format',
            'dropzone_message' => 'قم بسحب وإسقاط الملف هنا أو انقر للتحميل',
            'allowed_extensions' => 'اختر ملفًا بالامتدادات التالية: :extensions.',
            'import_button' => 'استيراد',
            'chunk_size' => 'حجم القطعة',
            'chunk_size_helper' => 'يتم تحديد عدد الصفوف التي سيتم استيرادها في المرة الواحدة حسب حجم القطعة. قم بزيادة هذه القيمة إذا كان لديك ملف كبير ويتم استيراد البيانات بسرعة كبيرة. قم بتقليل هذه القيمة إذا واجهت حدودًا للذاكرة أو مشكلات في مهلة البوابة عند استيراد البيانات.',
        ],

        'failures' => [
            'title' => 'الفشل',
            'attribute' => 'السمة',
            'errors' => 'أخطاء',
        ],

        'example' => [
            'title' => 'مثال',
            'download' => 'تحميل ملف :type النموذجي',
        ],

        'rules' => [
            'title' => 'القواعد',
            'column' => 'عمود',
        ],

        'uploading_message' => 'بدء تحميل الملف...',
        'uploaded_message' => 'تم تحميل الملف :file بنجاح. بدء التحقق من البيانات...',
        'validating_message' => 'التحقق من :from إلى :to...',
        'importing_message' => 'الاستيراد من :from إلى :to...',
        'done_message' => 'تم استيراد :count :label بنجاح.',
        'validating_failed_message' => 'فشل التحقق من الصحة. يرجى التحقق من الأخطاء أدناه.',
        'no_data_message' => 'بياناتك محدثة بالفعل أو لا توجد بيانات لاستيرادها.',
    ],

    'export' => [
        'name' => 'تصدير',
        'heading' => 'تصدير :label',
        'excel_not_supported_for_large_exports' => 'تنسيق Excel غير مدعوم للصادرات الكبيرة (:count عنصر). يرجى استخدام تنسيق CSV للحصول على أداء وموثوقية أفضل.',

        'form' => [
            'all_columns_disabled' => 'سيتم تصدير الأعمدة التالية: :columns.',
            'columns' => 'أعمدة',
            'format' => 'التنسيق',
            'export_button' => 'تصدير',
        ],

        'success_message' => 'تم التصدير بنجاح.',
        'error_message' => 'فشل التصدير.',

        'empty_state' => [
            'title' => 'لا توجد بيانات للتصدير',
            'description' => 'يبدو أنه لا توجد بيانات للتصدير.',
            'back' => 'العودة إلى :page',
        ],
    ],
    'check_all' => 'تحديد الكل',
];