<?php

return [
    'tools' => [
        'export_import_data' => 'ייצוא/ייבוא נתונים',
    ],

    'import' => [
        'name' => 'ייבוא',
        'heading' => 'ייבוא :label',
        'failed_to_read_file' => 'הקובץ אינו תקין, פגום או גדול מדי לקריאה.',

        'form' => [
            'quick_export_message' => 'אם ברצונך לייצא נתוני :label, תוכל לעשות זאת במהירות על ידי לחיצה על :export_csv_link או :export_excel_link.',
            'quick_export_button' => 'ייצוא ל-:format',
            'dropzone_message' => 'גרור ושחרר קובץ כאן או לחץ להעלאה',
            'allowed_extensions' => 'בחר קובץ עם הסיומות הבאות: :extensions.',
            'import_button' => 'ייבוא',
            'chunk_size' => 'גודל קטע',
            'chunk_size_helper' => 'מספר השורות שיש לייבא בכל פעם מוגדר על ידי גודל הקטע. הגדל ערך זה אם יש לך קובץ גדול והנתונים מיובאים מהר מאוד. הקטן ערך זה אם אתה נתקל במגבלות זיכרון או בעיות פסק זמן של שער בעת ייבוא נתונים.',
        ],

        'failures' => [
            'title' => 'כשלונות',
            'attribute' => 'תכונה',
            'errors' => 'שגיאות',
        ],

        'example' => [
            'title' => 'דוגמה',
            'download' => 'הורדת קובץ :type לדוגמה',
        ],

        'rules' => [
            'title' => 'כללים',
            'column' => 'עמודה',
        ],

        'uploading_message' => 'מתחיל להעלות קובץ...',
        'uploaded_message' => 'הקובץ :file הועלה בהצלחה. מתחיל לאמת נתונים...',
        'validating_message' => 'מאמת מ-:from עד :to...',
        'importing_message' => 'מייבא מ-:from עד :to...',
        'done_message' => 'יובאו בהצלחה :count :label.',
        'validating_failed_message' => 'האימות נכשל. אנא בדוק את השגיאות למטה.',
        'no_data_message' => 'הנתונים שלך כבר מעודכנים או שאין נתונים לייבוא.',
    ],

    'export' => [
        'name' => 'ייצוא',
        'heading' => 'ייצוא :label',
        'excel_not_supported_for_large_exports' => 'פורמט Excel אינו נתמך עבור ייצוא גדול (:count פריטים). אנא השתמש בפורמט CSV במקום לביצועים ואמינות טובים יותר.',

        'form' => [
            'all_columns_disabled' => 'העמודות הבאות ייוצאו: :columns.',
            'columns' => 'עמודות',
            'format' => 'פורמט',
            'export_button' => 'ייצוא',
        ],

        'success_message' => 'יוצא בהצלחה.',
        'error_message' => 'הייצוא נכשל.',

        'empty_state' => [
            'title' => 'אין נתונים לייצוא',
            'description' => 'נראה שאין נתונים לייצוא.',
            'back' => 'חזרה ל-:page',
        ],
    ],
    'check_all' => 'בחר הכל',
];
