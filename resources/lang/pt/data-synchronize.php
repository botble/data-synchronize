<?php

return [
    'tools' => [
        'export_import_data' => 'Exportar/Importar dados',
    ],

    'import' => [
        'name' => 'Importar',
        'heading' => 'Importar :label',
        'failed_to_read_file' => 'O ficheiro é inválido, corrompido ou demasiado grande para ler.',

        'form' => [
            'quick_export_message' => 'Se pretende exportar dados de :label, pode fazê-lo rapidamente clicando em :export_csv_link ou :export_excel_link.',
            'quick_export_button' => 'Exportar para :format',
            'dropzone_message' => 'Arraste e solte o ficheiro aqui ou clique para carregar',
            'allowed_extensions' => 'Escolha um ficheiro com as seguintes extensões: :extensions.',
            'import_button' => 'Importar',
            'chunk_size' => 'Tamanho do bloco',
            'chunk_size_helper' => 'O número de linhas a serem importadas de cada vez é definido pelo tamanho do bloco. Aumente este valor se tiver um ficheiro grande e os dados forem importados muito rapidamente. Diminua este valor se encontrar limites de memória ou problemas de tempo limite de gateway ao importar dados.',
        ],

        'failures' => [
            'title' => 'Falhas',
            'attribute' => 'Atributo',
            'errors' => 'Erros',
        ],

        'example' => [
            'title' => 'Exemplo',
            'download' => 'Descarregar ficheiro :type de exemplo',
        ],

        'rules' => [
            'title' => 'Regras',
            'column' => 'Coluna',
        ],

        'uploading_message' => 'A iniciar o carregamento do ficheiro...',
        'uploaded_message' => 'O ficheiro :file foi carregado com sucesso. A iniciar a validação de dados...',
        'validating_message' => 'A validar de :from a :to...',
        'importing_message' => 'A importar de :from a :to...',
        'done_message' => 'Importados com sucesso :count :label.',
        'validating_failed_message' => 'A validação falhou. Verifique os erros abaixo.',
        'no_data_message' => 'Os seus dados já estão atualizados ou não há dados para importar.',
    ],

    'export' => [
        'name' => 'Exportar',
        'heading' => 'Exportar :label',
        'excel_not_supported_for_large_exports' => 'O formato Excel não é suportado para grandes exportações (:count itens). Use o formato CSV para melhor desempenho e fiabilidade.',

        'form' => [
            'all_columns_disabled' => 'As seguintes colunas serão exportadas: :columns.',
            'columns' => 'Colunas',
            'format' => 'Formato',
            'export_button' => 'Exportar',
        ],

        'success_message' => 'Exportado com sucesso.',
        'error_message' => 'A exportação falhou.',

        'empty_state' => [
            'title' => 'Sem dados para exportar',
            'description' => 'Parece que não há dados para exportar.',
            'back' => 'Voltar a :page',
        ],
    ],
    'check_all' => 'Selecionar tudo',
];
