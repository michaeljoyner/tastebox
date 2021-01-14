<?php


namespace App\Orders;


class BatchIngredientsList
{
    private array $list;

    public function __construct()
    {
        $this->list = [];
    }

    public function addIngredient($ingredient, $meal_name, $servings)
    {
        if (!array_key_exists($ingredient['id'], $this->list)) {
            $this->list[$ingredient['id']] = [
                'id'          => $ingredient['id'],
                'description' => $ingredient['description'],
                'total_servings' => $servings,
                'uses'        => [
                    [
                        'meal'     => $meal_name,
                        'count'    => $servings,
                        'quantity' => $ingredient['quantity'],
                        'form' => $ingredient['form'] ?? 'n/a',
                    ],
                ]
            ];

            return;
        }

        $this->list[$ingredient['id']]['uses'][] = [
            'meal'     => $meal_name,
            'count'    => $servings,
            'quantity' => $ingredient['quantity'],
            'form' => $ingredient['form'] ?? 'n/a',
        ];

        $this->list[$ingredient['id']]['total_servings'] = $this->list[$ingredient['id']]['total_servings'] + $servings;
    }

    public function toArray(): array
    {
        return collect($this->list)->values()->all();
    }
}
