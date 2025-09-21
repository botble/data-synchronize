<?php

return [
    'tools' => [
        'export_import_data' => 'Xuất/Nhập dữ liệu',
    ],

    'import' => [
        'name' => 'Nhập',
        'heading' => 'Nhập :label',
        'failed_to_read_file' => 'Tệp không hợp lệ hoặc bị hỏng hoặc quá lớn để đọc.',

        'form' => [
            'quick_export_message' => 'Nếu bạn muốn xuất dữ liệu :label, bạn có thể thực hiện nhanh chóng bằng cách nhấp vào :export_csv_link hoặc :export_excel_link.',
            'quick_export_button' => 'Xuất sang :format',
            'dropzone_message' => 'Kéo và thả file vào đây hoặc click để tải lên',
            'allowed_extensions' => 'Chọn tệp có phần mở rộng sau: :extensions.',
            'import_button' => 'Nhập',
            'chunk_size' => 'Kích thước đoạn',
            'chunk_size_helper' => 'Số lượng hàng được nhập tại một thời điểm được xác định bởi kích thước khối. Tăng giá trị này nếu bạn có tệp lớn và dữ liệu được nhập rất nhanh. Hãy giảm giá trị này nếu bạn gặp phải vấn đề về giới hạn bộ nhớ hoặc thời gian chờ của cổng khi nhập dữ liệu.',
        ],

        'failures' => [
            'title' => 'Thất bại',
            'attribute' => 'Thuộc tính',
            'errors' => 'Lỗi',
        ],

        'example' => [
            'title' => 'Ví dụ',
            'download' => 'Tải xuống tệp :type mẫu',
        ],

        'rules' => [
            'title' => 'Quy tắc',
            'column' => 'Cột',
        ],

        'uploading_message' => 'Đang bắt đầu tải tệp lên...',
        'uploaded_message' => 'Tệp :file đã được tải lên thành công. Bắt đầu xác thực dữ liệu...',
        'validating_message' => 'Đang xác thực từ :from đến :to...',
        'importing_message' => 'Đang nhập từ :from đến :to...',
        'done_message' => 'Đã nhập :count :label thành công.',
        'validating_failed_message' => 'Xác thực không thành công. Vui lòng kiểm tra các lỗi dưới đây.',
        'no_data_message' => 'Dữ liệu của bạn đã được cập nhật hoặc không có dữ liệu để nhập.',
    ],

    'export' => [
        'name' => 'Xuất',
        'heading' => 'Xuất :label',
        'excel_not_supported_for_large_exports' => 'Định dạng Excel không hỗ trợ xuất dữ liệu lớn (:count mục). Vui lòng sử dụng định dạng CSV để có hiệu suất và độ tin cậy tốt hơn.',

        'form' => [
            'all_columns_disabled' => 'Các cột sau sẽ được xuất: :columns.',
            'columns' => 'Cột',
            'format' => 'Định dạng',
            'export_button' => 'Xuất',
        ],

        'success_message' => 'Đã xuất thành công.',
        'error_message' => 'Xuất không thành công.',

        'empty_state' => [
            'title' => 'Không có dữ liệu để xuất',
            'description' => 'Có vẻ như không có dữ liệu để xuất.',
            'back' => 'Quay lại :page',
        ],
    ],
    'check_all' => 'Chọn tất cả',
];