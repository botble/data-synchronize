<?php

return [
    'tools' => [
        'export_import_data' => 'Exporter/Importer des données',
    ],

    'import' => [
        'name' => 'Importer',
        'heading' => 'Importer :label',
        'failed_to_read_file' => 'Le fichier est invalide, corrompu ou trop volumineux pour être lu.',

        'form' => [
            'quick_export_message' => 'Si vous souhaitez exporter les données :label, vous pouvez le faire rapidement en cliquant sur :export_csv_link ou :export_excel_link.',
            'quick_export_button' => 'Exporter vers :format',
            'dropzone_message' => 'Glissez-déposez le fichier ici ou cliquez pour télécharger',
            'allowed_extensions' => 'Choisissez un fichier avec les extensions suivantes : :extensions.',
            'import_button' => 'Importer',
            'chunk_size' => 'Taille du bloc',
            'chunk_size_helper' => 'Le nombre de lignes à importer à la fois est défini par la taille du bloc. Augmentez cette valeur si vous avez un fichier volumineux et que les données sont importées très rapidement. Diminuez cette valeur si vous rencontrez des limites de mémoire ou des problèmes de délai d\'attente de la passerelle lors de l\'importation de données.',
        ],

        'failures' => [
            'title' => 'Échecs',
            'attribute' => 'Attribut',
            'errors' => 'Erreurs',
        ],

        'example' => [
            'title' => 'Exemple',
            'download' => 'Télécharger un exemple de fichier :type',
        ],

        'rules' => [
            'title' => 'Règles',
            'column' => 'Colonne',
        ],

        'uploading_message' => 'Début du téléchargement du fichier...',
        'uploaded_message' => 'Le fichier :file a été téléchargé avec succès. Début de la validation des données...',
        'validating_message' => 'Validation de :from à :to...',
        'importing_message' => 'Importation de :from à :to...',
        'done_message' => ':count :label importés avec succès.',
        'validating_failed_message' => 'La validation a échoué. Veuillez vérifier les erreurs ci-dessous.',
        'no_data_message' => 'Vos données sont déjà à jour ou il n\'y a pas de données à importer.',
    ],

    'export' => [
        'name' => 'Exporter',
        'heading' => 'Exporter :label',
        'excel_not_supported_for_large_exports' => 'Le format Excel n\'est pas pris en charge pour les exportations volumineuses (:count éléments). Veuillez utiliser le format CSV pour de meilleures performances et fiabilité.',

        'form' => [
            'all_columns_disabled' => 'Les colonnes suivantes seront exportées : :columns.',
            'columns' => 'Colonnes',
            'format' => 'Format',
            'export_button' => 'Exporter',
        ],

        'success_message' => 'Exporté avec succès.',
        'error_message' => 'L\'exportation a échoué.',

        'empty_state' => [
            'title' => 'Aucune donnée à exporter',
            'description' => 'Il semble qu\'il n\'y ait pas de données à exporter.',
            'back' => 'Retour à :page',
        ],
    ],
    'check_all' => 'Tout sélectionner',
];
