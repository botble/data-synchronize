<?php

return [
    'tools' => [
        'export_import_data' => 'Извоз/Увоз података',
    ],

    'import' => [
        'name' => 'Увоз',
        'heading' => 'Увоз :label',
        'failed_to_read_file' => 'Фајл је неважећи, оштећен или превелик за читање.',

        'form' => [
            'quick_export_message' => 'Ако желите да извезете :label податке, можете то брзо учинити кликом на :export_csv_link или :export_excel_link.',
            'quick_export_button' => 'Извези у :format',
            'dropzone_message' => 'Превуците и испустите фајл овде или кликните за отпремање',
            'allowed_extensions' => 'Изаберите фајл са следећим екстензијама: :extensions.',
            'import_button' => 'Увези',
            'chunk_size' => 'Величина дела',
            'chunk_size_helper' => 'Број редова који ће бити увезени одједном дефинисан је величином дела. Повећајте ову вредност ако имате велики фајл и подаци се увозе веома брзо. Смањите ову вредност ако наиђете на ограничења меморије или проблеме са истеком времена приступника приликом увоза података.',
        ],

        'failures' => [
            'title' => 'Неуспеси',
            'attribute' => 'Атрибут',
            'errors' => 'Грешке',
        ],

        'example' => [
            'title' => 'Пример',
            'download' => 'Преузми пример :type фајла',
        ],

        'rules' => [
            'title' => 'Правила',
            'column' => 'Колона',
        ],

        'uploading_message' => 'Почиње отпремање фајла...',
        'uploaded_message' => 'Фајл :file је успешно отпремљен. Почиње валидација података...',
        'validating_message' => 'Валидација од :from до :to...',
        'importing_message' => 'Увоз од :from до :to...',
        'done_message' => 'Успешно увезено :count :label.',
        'validating_failed_message' => 'Валидација није успела. Проверите грешке испод.',
        'no_data_message' => 'Ваши подаци су већ ажурирани или нема података за увоз.',
    ],

    'export' => [
        'name' => 'Извоз',
        'heading' => 'Извоз :label',
        'excel_not_supported_for_large_exports' => 'Excel формат није подржан за велике извозе (:count ставки). Молимо користите CSV формат за бољу перформансу и поузданост.',

        'form' => [
            'all_columns_disabled' => 'Следеће колоне ће бити извезене: :columns.',
            'columns' => 'Колоне',
            'format' => 'Формат',
            'export_button' => 'Извези',
        ],

        'success_message' => 'Успешно извезено.',
        'error_message' => 'Извоз није успео.',

        'empty_state' => [
            'title' => 'Нема података за извоз',
            'description' => 'Изгледа да нема података за извоз.',
            'back' => 'Назад на :page',
        ],
    ],
    'check_all' => 'Означи све',
];
