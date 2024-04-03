<?php

return [

    'label' => 'Grupo de Clientes',

    'plural_label' => 'Grupos de Clientes',

    'table' => [
        'name' => [
            'label' => 'Nome',
        ],
        'handle' => [
            'label' => 'Identificador',
        ],
        'default' => [
            'label' => 'Padrão',
        ],
    ],

    'form' => [
        'name' => [
            'label' => 'Nome',
        ],
        'handle' => [
            'label' => 'Identificador',
        ],
        'default' => [
            'label' => 'Padrão',
        ],
    ],

    'action' => [
        'delete' => [
            'notification' => [
                'error_protected' => 'Este grupo de clientes não pode ser excluído pois há clientes associados.',
            ],
        ],
    ],
];
