<?php

return [
    'tools' => [
        'export_import_data' => 'Exportar/Importar dados',
    ],

    'import' => [
        'name' => 'Importar',
        'heading' => 'Importar :label',
        'failed_to_read_file' => 'O arquivo é inválido, corrompido ou muito grande para ler.',

        'form' => [
            'quick_export_message' => 'Se você deseja exportar dados de :label, pode fazê-lo rapidamente clicando em :export_csv_link ou :export_excel_link.',
            'quick_export_button' => 'Exportar para :format',
            'dropzone_message' => 'Arraste e solte o arquivo aqui ou clique para fazer upload',
            'allowed_extensions' => 'Escolha um arquivo com as seguintes extensões: :extensions.',
            'import_button' => 'Importar',
            'chunk_size' => 'Tamanho do lote',
            'chunk_size_helper' => 'O número de linhas a serem importadas de cada vez é definido pelo tamanho do lote. Aumente este valor se você tiver um arquivo grande e os dados forem importados muito rapidamente. Diminua este valor se encontrar limites de memória ou problemas de timeout de gateway ao importar dados.',
        ],

        'failures' => [
            'title' => 'Falhas',
            'attribute' => 'Atributo',
            'errors' => 'Erros',
        ],

        'example' => [
            'title' => 'Exemplo',
            'download' => 'Baixar arquivo :type de exemplo',
        ],

        'rules' => [
            'title' => 'Regras',
            'column' => 'Coluna',
        ],

        'uploading_message' => 'Iniciando o upload do arquivo...',
        'uploaded_message' => 'O arquivo :file foi carregado com sucesso. Iniciando a validação de dados...',
        'validating_message' => 'Validando de :from a :to...',
        'importing_message' => 'Importando de :from a :to...',
        'done_message' => 'Importados com sucesso :count :label.',
        'validating_failed_message' => 'A validação falhou. Verifique os erros abaixo.',
        'no_data_message' => 'Seus dados já estão atualizados ou não há dados para importar.',
    ],

    'export' => [
        'name' => 'Exportar',
        'heading' => 'Exportar :label',
        'excel_not_supported_for_large_exports' => 'O formato Excel não é suportado para grandes exportações (:count itens). Use o formato CSV para melhor desempenho e confiabilidade.',

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
            'back' => 'Voltar para :page',
        ],
    ],
    'check_all' => 'Selecionar tudo',
];
