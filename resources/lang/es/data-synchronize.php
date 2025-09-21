<?php

return [
    'tools' => [
        'export_import_data' => 'Exportar/Importar datos',
    ],

    'import' => [
        'name' => 'Importar',
        'heading' => 'Importar :label',
        'failed_to_read_file' => 'El archivo no es válido, está dañado o es demasiado grande para leerlo.',

        'form' => [
            'quick_export_message' => 'Si desea exportar datos de :label, puede hacerlo rápidamente haciendo clic en :export_csv_link o :export_excel_link.',
            'quick_export_button' => 'Exportar a :format',
            'dropzone_message' => 'Arrastra y suelta el archivo aquí o haz clic para cargarlo.',
            'allowed_extensions' => 'Elija un archivo con las siguientes extensiones: :extensions.',
            'import_button' => 'Importar',
            'chunk_size' => 'Tamaño del bloque',
            'chunk_size_helper' => 'El número de filas que se importarán a la vez está definido por el tamaño del fragmento. Aumente este valor si tiene un archivo grande y los datos se importan muy rápido. Disminuya este valor si encuentra límites de memoria o problemas de tiempo de espera de la puerta de enlace al importar datos.',
        ],

        'failures' => [
            'title' => 'Fallos',
            'attribute' => 'Atributo',
            'errors' => 'Errores',
        ],

        'example' => [
            'title' => 'Ejemplo',
            'download' => 'Descargar archivo :type de ejemplo',
        ],

        'rules' => [
            'title' => 'Reglas',
            'column' => 'Columna',
        ],

        'uploading_message' => 'Empezando a cargar el archivo...',
        'uploaded_message' => 'El archivo :file se ha cargado correctamente. Comenzando a validar datos...',
        'validating_message' => 'Validando desde :from hasta :to...',
        'importing_message' => 'Importando desde :from hasta :to...',
        'done_message' => ':count :label importados con éxito.',
        'validating_failed_message' => 'La validación falló. Por favor verifique los errores a continuación.',
        'no_data_message' => 'Tus datos ya están actualizados o no hay datos para importar.',
    ],

    'export' => [
        'name' => 'Exportar',
        'heading' => 'Exportar :label',
        'excel_not_supported_for_large_exports' => 'El formato Excel no es compatible con exportaciones grandes (:count elementos). Por favor, use el formato CSV para mejor rendimiento y confiabilidad.',

        'form' => [
            'all_columns_disabled' => 'Se exportarán las siguientes columnas: :columns.',
            'columns' => 'Columnas',
            'format' => 'Formato',
            'export_button' => 'Exportar',
        ],

        'success_message' => 'Exportado exitosamente.',
        'error_message' => 'La exportación falló.',

        'empty_state' => [
            'title' => 'No hay datos para exportar',
            'description' => 'Parece que no hay datos para exportar.',
            'back' => 'Volver a :page',
        ],
    ],
    'check_all' => 'Seleccionar todo',
];