<?php


namespace App\Meals;


class NutritionalInfo
{

    public ?int $energy;
    public ?int $carbs;
    public ?int $fat;
    public ?int $protein;

    public function __construct($info)
    {
        $this->energy = $info['serving_energy'] ?? null;
        $this->carbs = $info['serving_carbs'] ?? null;
        $this->fat = $info['serving_fat'] ?? null;
        $this->protein = $info['serving_protein'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'serving_energy'  => $this->energy,
            'serving_carbs'   => $this->carbs,
            'serving_fat'     => $this->fat,
            'serving_protein' => $this->protein,
        ];
    }
}
