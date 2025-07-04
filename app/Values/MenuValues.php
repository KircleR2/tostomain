<?php

namespace App\Values;

abstract class MenuValues
{
    public const BREAKFAST_ALL_DAY = [
        'label' => 'Desayunos todo el día',
        'value' => 'desayunos-todo-el-dia'
    ];

    public const TO_SHARE = [
        'label' => 'Para Compartir',
        'value' => 'para-compartir'
    ];

    public const SANDWICHES_BURGERS = [
        'label' => 'Sandwiches & Burgers',
        'value' => 'sandwiches-y-burgers'
    ];

    public const LUNCH_DINNER = [
        'label' => 'Lunch & Dinner',
        'value' => 'lunch-y-dinner'
    ];

    public const SALAD_BOWLS = [
        'label' => 'Salad Bowls',
        'value' => 'salad-bowls'
    ];

    public const KIDS_MENU = [
        'label' => 'Kids Menu',
        'value' => 'kids-menu'
    ];

    public const COFFEE_AND_HOT_DRINKS = [
        'label' => 'Café y Bebidas Calientes',
        'value' => 'cafe-y-bebidas-calientes'
    ];

    public const COLD_DRINKS_AND_FRESH = [
        'label' => 'Cold Drinks & Fresh',
        'value' => 'cold-drinks-and-fresh'
    ];

    public const TOSTO_BAR = [
        'label' => 'Tosto Bar',
        'value' => 'tosto-bar'
    ];

    public const CHEF_SPECIALTIES= [
        'label' => 'Especialidades del Chef',
        'value' => 'especialidades-del-chef'
    ];

    public static function getList (): array
    {
        return [
            self::CHEF_SPECIALTIES,
            self::BREAKFAST_ALL_DAY,
            self::TO_SHARE,
            self::SANDWICHES_BURGERS,
            self::LUNCH_DINNER,
            self::SALAD_BOWLS,
            self::KIDS_MENU,
            self::COFFEE_AND_HOT_DRINKS,
            self::COLD_DRINKS_AND_FRESH,
            self::TOSTO_BAR,
        ];
    }
}
