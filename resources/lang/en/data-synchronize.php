<?php

return [
    'tools' => [
        'export_import_data' => 'Export/Import Data',
    ],

    'import' => [
        'name' => 'Import',
        'heading' => 'Import :label',
        'failed_to_read_file' => 'The file is invalid or corrupted or too large to read.',

        'form' => [
            'quick_export_message' => 'If you want to export :label data, you can do it quickly by clicking on :export_csv_link or :export_excel_link.',
            'quick_export_button' => 'Export to :format',
            'dropzone_message' => 'Drag and drop file here or click to upload',
            'allowed_extensions' => 'Choose a file with following extensions: :extensions.',
            'import_button' => 'Import',
            'chunk_size' => 'Chunk size',
            'chunk_size_helper' => 'The number of rows to be imported at a time is defined by the chunk size. Increase this value if you have a large file and data is imported very fast. Decrease this value if you encounter memory limits or gateway timeout issues when importing data.',
        ],

        'failures' => [
            'title' => 'Failures',
            'attribute' => 'Attribute',
            'errors' => 'Errors',
        ],

        'example' => [
            'title' => 'Example',
            'download' => 'Download example :type file',
        ],

        'rules' => [
            'title' => 'Rules',
            'column' => 'Column',
        ],

        'uploading_message' => 'Starting to upload file...',
        'uploaded_message' => 'File :file has been uploaded successfully. Start validating data...',
        'validating_message' => 'Validating from :from to :to...',
        'importing_message' => 'Importing from :from to :to...',
        'done_message' => 'Imported :count :label successfully.',
        'validating_failed_message' => 'Validating failed. Please check the errors below.',
        'no_data_message' => 'Your data is already up to date or no data to import.',
    ],

    'export' => [
        'name' => 'Export',
        'heading' => 'Export :label',

        'form' => [
            'all_columns_disabled' => 'Following columns will be exported: :columns.',
            'columns' => 'Columns',
            'format' => 'Format',
            'export_button' => 'Export',
        ],

        'success_message' => 'Exported successfully.',
        'error_message' => 'Export failed.',

        'empty_state' => [
            'title' => 'No data to export',
            'description' => 'Looks like there is no data to export.',
            'back' => 'Back to :page',
        ],
    ],
    'check_all' => 'Check all',
];
