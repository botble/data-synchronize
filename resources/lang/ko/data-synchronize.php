<?php

return [
    'tools' => [
        'export_import_data' => '데이터 내보내기/가져오기',
    ],

    'import' => [
        'name' => '가져오기',
        'heading' => ':label 가져오기',
        'failed_to_read_file' => '파일이 유효하지 않거나 손상되었거나 읽기에는 너무 큽니다.',

        'form' => [
            'quick_export_message' => ':label 데이터를 내보내려면 :export_csv_link 또는 :export_excel_link를 클릭하여 빠르게 수행할 수 있습니다.',
            'quick_export_button' => ':format로 내보내기',
            'dropzone_message' => '여기에 파일을 끌어다 놓거나 클릭하여 업로드하세요',
            'allowed_extensions' => '다음 확장자를 가진 파일을 선택하세요: :extensions.',
            'import_button' => '가져오기',
            'chunk_size' => '청크 크기',
            'chunk_size_helper' => '한 번에 가져올 행 수는 청크 크기로 정의됩니다. 큰 파일이 있고 데이터가 매우 빠르게 가져와지는 경우 이 값을 늘리세요. 데이터를 가져올 때 메모리 제한이나 게이트웨이 시간 초과 문제가 발생하면 이 값을 줄이세요.',
        ],

        'failures' => [
            'title' => '실패',
            'attribute' => '속성',
            'errors' => '오류',
        ],

        'example' => [
            'title' => '예제',
            'download' => '예제 :type 파일 다운로드',
        ],

        'rules' => [
            'title' => '규칙',
            'column' => '열',
        ],

        'uploading_message' => '파일 업로드 시작...',
        'uploaded_message' => '파일 :file이(가) 성공적으로 업로드되었습니다. 데이터 유효성 검사 시작...',
        'validating_message' => ':from에서 :to까지 유효성 검사 중...',
        'importing_message' => ':from에서 :to까지 가져오는 중...',
        'done_message' => ':count개의 :label을(를) 성공적으로 가져왔습니다.',
        'validating_failed_message' => '유효성 검사에 실패했습니다. 아래의 오류를 확인하세요.',
        'no_data_message' => '데이터가 이미 최신이거나 가져올 데이터가 없습니다.',
    ],

    'export' => [
        'name' => '내보내기',
        'heading' => ':label 내보내기',
        'excel_not_supported_for_large_exports' => '대용량 내보내기(:count개 항목)에는 Excel 형식이 지원되지 않습니다. 더 나은 성능과 안정성을 위해 CSV 형식을 사용하세요.',

        'form' => [
            'all_columns_disabled' => '다음 열이 내보내집니다: :columns.',
            'columns' => '열',
            'format' => '형식',
            'export_button' => '내보내기',
        ],

        'success_message' => '성공적으로 내보냈습니다.',
        'error_message' => '내보내기에 실패했습니다.',

        'empty_state' => [
            'title' => '내보낼 데이터가 없습니다',
            'description' => '내보낼 데이터가 없는 것 같습니다.',
            'back' => ':page(으)로 돌아가기',
        ],
    ],
    'check_all' => '모두 선택',
];
