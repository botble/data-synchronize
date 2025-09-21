<?php

return [
    'tools' => [
        'export_import_data' => 'データのエクスポート/インポート',
    ],

    'import' => [
        'name' => 'インポート',
        'heading' => ':label をインポート',
        'failed_to_read_file' => 'ファイルが無効、破損している、または大きすぎて読み取れません。',

        'form' => [
            'quick_export_message' => ':label データをエクスポートしたい場合は、:export_csv_link または :export_excel_link をクリックして簡単に実行できます。',
            'quick_export_button' => ':format にエクスポート',
            'dropzone_message' => 'ここにファイルをドラッグ＆ドロップするか、クリックしてアップロード',
            'allowed_extensions' => '次の拡張子を持つファイルを選択してください: :extensions。',
            'import_button' => 'インポート',
            'chunk_size' => 'チャンクサイズ',
            'chunk_size_helper' => '一度にインポートされる行数はチャンクサイズによって定義されます。大きなファイルがあり、データが非常に高速にインポートされる場合はこの値を増やしてください。データをインポートする際にメモリ制限またはゲートウェイタイムアウトの問題が発生した場合はこの値を減らしてください。',
        ],

        'failures' => [
            'title' => '失敗',
            'attribute' => '属性',
            'errors' => 'エラー',
        ],

        'example' => [
            'title' => '例',
            'download' => 'サンプル :type ファイルをダウンロード',
        ],

        'rules' => [
            'title' => 'ルール',
            'column' => 'カラム',
        ],

        'uploading_message' => 'ファイルのアップロードを開始しています...',
        'uploaded_message' => 'ファイル :file が正常にアップロードされました。データの検証を開始します...',
        'validating_message' => ':from から :to まで検証中...',
        'importing_message' => ':from から :to までインポート中...',
        'done_message' => ':count 件の :label を正常にインポートしました。',
        'validating_failed_message' => '検証に失敗しました。以下のエラーをご確認ください。',
        'no_data_message' => 'データは既に最新の状態か、インポートするデータがありません。',
    ],

    'export' => [
        'name' => 'エクスポート',
        'heading' => ':label をエクスポート',
        'excel_not_supported_for_large_exports' => 'Excel形式は大量のエクスポート（:count 項目）には対応していません。より良いパフォーマンスと信頼性のためにCSV形式をご使用ください。',

        'form' => [
            'all_columns_disabled' => '次の列がエクスポートされます: :columns。',
            'columns' => 'カラム',
            'format' => 'フォーマット',
            'export_button' => 'エクスポート',
        ],

        'success_message' => '正常にエクスポートされました。',
        'error_message' => 'エクスポートに失敗しました。',

        'empty_state' => [
            'title' => 'エクスポートするデータがありません',
            'description' => 'エクスポートするデータがないようです。',
            'back' => ':page に戻る',
        ],
    ],
    'check_all' => 'すべて選択',
];