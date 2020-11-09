<?php


namespace App\Meals;


class MealsPresenter
{
    public static function forAdmin(Meal $meal)
    {

        $gallery = $meal->getMedia(Meal::GALLERY)
                        ->sortBy(fn($m) => $m->getCustomProperty('position'))
                        ->map(fn($m) => [
                            'id'    => $m->id,
                            'thumb' => $m->getUrl('thumb'),
                            'web'   => $m->getUrl('web'),
                            'src'   => $m->getUrl('web'),
                        ])->values();;

        return [
            'id'                     => $meal->id,
            'unique_id'              => $meal->unique_id,
            'is_public'              => $meal->is_public,
            'name'                   => $meal->name,
            'description'            => $meal->description,
            'allergens'              => $meal->allergens,
            'prep_time'              => $meal->prep_time,
            'cook_time'              => $meal->cook_time,
            'instructions'           => $meal->instructions,
            'serving_energy'         => $meal->serving_energy,
            'serving_carbs'          => $meal->serving_carbs,
            'serving_fat'            => $meal->serving_fat,
            'serving_protein'        => $meal->serving_protein,
            'ingredients'            => $meal->ingredients()->orderBy('position')->get()->map->toArray()->all(),
            'title_image'            => $gallery->count() ? $gallery->first() : $meal->defaultImage(),
            'gallery'                => $gallery->all(),
            'classifications'        => $meal->classifications->map->toArray()->all(),
            'last_touched_timestamp' => max($meal->created_at->timestamp, $meal->updated_at->timestamp),
        ];
    }
}
