<?php

return [
    'tools' => [
        'export_import_data' => 'ส่งออก/นำเข้าข้อมูล',
    ],

    'import' => [
        'name' => 'นำเข้า',
        'heading' => 'นำเข้า :label',
        'failed_to_read_file' => 'ไฟล์ไม่ถูกต้อง เสียหาย หรือใหญ่เกินไปที่จะอ่าน',

        'form' => [
            'quick_export_message' => 'หากคุณต้องการส่งออกข้อมูล :label คุณสามารถทำได้อย่างรวดเร็วโดยคลิกที่ :export_csv_link หรือ :export_excel_link',
            'quick_export_button' => 'ส่งออกเป็น :format',
            'dropzone_message' => 'ลากและวางไฟล์ที่นี่หรือคลิกเพื่ออัปโหลด',
            'allowed_extensions' => 'เลือกไฟล์ที่มีนามสกุลต่อไปนี้: :extensions',
            'import_button' => 'นำเข้า',
            'chunk_size' => 'ขนาดชิ้น',
            'chunk_size_helper' => 'จำนวนแถวที่จะนำเข้าในแต่ละครั้งถูกกำหนดโดยขนาดชิ้น เพิ่มค่านี้หากคุณมีไฟล์ขนาดใหญ่และข้อมูลถูกนำเข้าอย่างรวดเร็วมาก ลดค่านี้หากคุณพบข้อจำกัดของหน่วยความจำหรือปัญหาการหมดเวลาของเกตเวย์เมื่อนำเข้าข้อมูล',
        ],

        'failures' => [
            'title' => 'ความล้มเหลว',
            'attribute' => 'แอตทริบิวต์',
            'errors' => 'ข้อผิดพลาด',
        ],

        'example' => [
            'title' => 'ตัวอย่าง',
            'download' => 'ดาวน์โหลดไฟล์ :type ตัวอย่าง',
        ],

        'rules' => [
            'title' => 'กฎ',
            'column' => 'คอลัมน์',
        ],

        'uploading_message' => 'กำลังเริ่มอัปโหลดไฟล์...',
        'uploaded_message' => 'ไฟล์ :file ถูกอัปโหลดเรียบร้อยแล้ว เริ่มตรวจสอบความถูกต้องของข้อมูล...',
        'validating_message' => 'กำลังตรวจสอบความถูกต้องจาก :from ถึง :to...',
        'importing_message' => 'กำลังนำเข้าจาก :from ถึง :to...',
        'done_message' => 'นำเข้า :count :label สำเร็จแล้ว',
        'validating_failed_message' => 'การตรวจสอบความถูกต้องล้มเหลว โปรดตรวจสอบข้อผิดพลาดด้านล่าง',
        'no_data_message' => 'ข้อมูลของคุณเป็นปัจจุบันอยู่แล้ว หรือไม่มีข้อมูลให้นำเข้า',
    ],

    'export' => [
        'name' => 'ส่งออก',
        'heading' => 'ส่งออก :label',
        'excel_not_supported_for_large_exports' => 'รูปแบบ Excel ไม่รองรับสำหรับการส่งออกขนาดใหญ่ (:count รายการ) โปรดใช้รูปแบบ CSV แทนเพื่อประสิทธิภาพและความน่าเชื่อถือที่ดีขึ้น',

        'form' => [
            'all_columns_disabled' => 'คอลัมน์ต่อไปนี้จะถูกส่งออก: :columns',
            'columns' => 'คอลัมน์',
            'format' => 'รูปแบบ',
            'export_button' => 'ส่งออก',
        ],

        'success_message' => 'ส่งออกสำเร็จแล้ว',
        'error_message' => 'การส่งออกล้มเหลว',

        'empty_state' => [
            'title' => 'ไม่มีข้อมูลให้ส่งออก',
            'description' => 'ดูเหมือนว่าไม่มีข้อมูลให้ส่งออก',
            'back' => 'กลับไปที่ :page',
        ],
    ],
    'check_all' => 'เลือกทั้งหมด',
];
