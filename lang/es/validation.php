<?php

return [
    'max' => [
        'string' => 'El campo :attribute no debe ser mayor que :max caracteres.',
    ],
    'custom' => [
        'specs.*.value' => [
            'max' => 'La especificación no debe superar :max caracteres.',
        ],
    ],
    'attributes' => [
        'specs.0.value' => 'especificación',
        'specs.*.value' => 'especificación',
    ],
];
