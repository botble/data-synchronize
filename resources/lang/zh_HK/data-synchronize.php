<?php

return [
    'tools' => [
        'export_import_data' => '匯出/匯入數據',
    ],
    'import' => [
        'name' => '匯入',
        'heading' => '匯入 :label',
        'failed_to_read_file' => '檔案無效、損壞或太大而無法讀取。',
        'form' => [
            'quick_export_message' => '如果你想匯出 :label 數據，你可以通過點擊 :export_csv_link 或 :export_excel_link 快速完成。',
            'quick_export_button' => '匯出到 :format',
            'dropzone_message' => '將文件拖放到這裡或點擊上傳',
            'allowed_extensions' => '選擇一個具有以下擴展名的文件：:extensions。',
            'import_button' => '匯入',
            'chunk_size' => '塊大小',
            'chunk_size_helper' => '一次匯入的行數由塊大小定義。如果你有一個大文件並且數據匯入得非常快，請增加這個值。如果在匯入數據時遇到內存限制或網關超時問題，請減少這個值。',
        ],
        'failures' => [
            'title' => '失敗',
            'attribute' => '屬性',
            'errors' => '錯誤',
        ],
        'example' => [
            'title' => '例子',
            'download' => '下載範例 :type 文件',
        ],
        'rules' => [
            'title' => '規則',
            'column' => '欄位',
        ],
        'uploading_message' => '開始上傳檔案...',
        'uploaded_message' => '檔案 :file 已成功上傳。開始驗證數據...',
        'validating_message' => '驗證從 :from 到 :to...',
        'importing_message' => '匯入從 :from 到 :to...',
        'done_message' => '成功匯入 :count 個 :label。',
        'validating_failed_message' => '驗證失敗。請檢查以下錯誤。',
        'no_data_message' => '你的數據已經是最新的，或者沒有數據可供匯入。',
    ],
    'export' => [
        'name' => '匯出',
        'heading' => '匯出 :label',
        'excel_not_supported_for_large_exports' => 'Excel 格式不支持大量匯出（:count 項）。請使用 CSV 格式以獲得更好的性能和可靠性。',

        'form' => [
            'all_columns_disabled' => '以下欄位將被匯出：:columns。',
            'columns' => '欄位',
            'format' => '格式',
            'export_button' => '匯出',
        ],
        'success_message' => '成功匯出。',
        'error_message' => '匯出失敗。',
        'empty_state' => [
            'title' => '冇數據可以匯出',
            'description' => '睇嚟冇數據可以匯出。',
            'back' => '返回 :page',
        ],
    ],
    'check_all' => '全選',
];
