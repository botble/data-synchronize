<?php

return [
    'tools' => [
        'export_import_data' => 'ডেটা রপ্তানি/আমদানি',
    ],

    'import' => [
        'name' => 'আমদানি',
        'heading' => ':label আমদানি করুন',
        'failed_to_read_file' => 'ফাইলটি অবৈধ বা দূষিত বা পড়ার জন্য খুব বড়।',

        'form' => [
            'quick_export_message' => 'আপনি যদি :label ডেটা রপ্তানি করতে চান, তাহলে :export_csv_link বা :export_excel_link এ ক্লিক করে দ্রুত করতে পারেন।',
            'quick_export_button' => ':format এ রপ্তানি করুন',
            'dropzone_message' => 'এখানে ফাইল টেনে আনুন বা আপলোড করতে ক্লিক করুন',
            'allowed_extensions' => 'নিম্নলিখিত এক্সটেনশন সহ একটি ফাইল নির্বাচন করুন: :extensions।',
            'import_button' => 'আমদানি',
            'chunk_size' => 'চাঙ্ক সাইজ',
            'chunk_size_helper' => 'একসাথে আমদানি করা সারির সংখ্যা চাঙ্ক সাইজ দ্বারা নির্ধারিত হয়। আপনার কাছে একটি বড় ফাইল থাকলে এবং ডেটা খুব দ্রুত আমদানি হলে এই মান বাড়ান। ডেটা আমদানি করার সময় মেমরি সীমা বা গেটওয়ে টাইমআউট সমস্যার সম্মুখীন হলে এই মান কমান।',
        ],

        'failures' => [
            'title' => 'ব্যর্থতা',
            'attribute' => 'বৈশিষ্ট্য',
            'errors' => 'ত্রুটি',
        ],

        'example' => [
            'title' => 'উদাহরণ',
            'download' => 'উদাহরণ :type ফাইল ডাউনলোড করুন',
        ],

        'rules' => [
            'title' => 'নিয়ম',
            'column' => 'কলাম',
        ],

        'uploading_message' => 'ফাইল আপলোড শুরু হচ্ছে...',
        'uploaded_message' => 'ফাইল :file সফলভাবে আপলোড করা হয়েছে। ডেটা যাচাইকরণ শুরু করুন...',
        'validating_message' => ':from থেকে :to পর্যন্ত যাচাই করা হচ্ছে...',
        'importing_message' => ':from থেকে :to পর্যন্ত আমদানি করা হচ্ছে...',
        'done_message' => 'সফলভাবে :count :label আমদানি করা হয়েছে।',
        'validating_failed_message' => 'যাচাইকরণ ব্যর্থ হয়েছে। নিচের ত্রুটিগুলি পরীক্ষা করুন।',
        'no_data_message' => 'আপনার ডেটা ইতিমধ্যে আপ টু ডেট বা আমদানির জন্য কোন ডেটা নেই।',
    ],

    'export' => [
        'name' => 'রপ্তানি',
        'heading' => ':label রপ্তানি করুন',
        'excel_not_supported_for_large_exports' => 'বড় রপ্তানির জন্য Excel ফরম্যাট সমর্থিত নয় (:count আইটেম)। আরও ভাল কার্যকারিতা এবং নির্ভরযোগ্যতার জন্য পরিবর্তে CSV ফরম্যাট ব্যবহার করুন।',

        'form' => [
            'all_columns_disabled' => 'নিম্নলিখিত কলামগুলি রপ্তানি করা হবে: :columns।',
            'columns' => 'কলাম',
            'format' => 'ফরম্যাট',
            'export_button' => 'রপ্তানি',
        ],

        'success_message' => 'সফলভাবে রপ্তানি করা হয়েছে।',
        'error_message' => 'রপ্তানি ব্যর্থ হয়েছে।',

        'empty_state' => [
            'title' => 'রপ্তানির জন্য কোন ডেটা নেই',
            'description' => 'দেখে মনে হচ্ছে রপ্তানির জন্য কোন ডেটা নেই।',
            'back' => ':page এ ফিরে যান',
        ],
    ],
    'check_all' => 'সব চেক করুন',
];
