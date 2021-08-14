<?php


namespace App\Meals;


use App\DatePresenter;
use App\Orders\MealTally;
use Illuminate\Support\Carbon;

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
                        ])->values();

        $last_used = $meal->last_used ? Carbon::parse($meal->last_used) : null;
        $recent_usage = self::getMostRecentUsedDate($last_used, $meal->tallies);

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
            'recent_inclusion'       => DatePresenter::pretty($last_used),
            'upcoming'               => $last_used?->isFuture() ? $last_used?->diffForHumans() : false,
            'times_offered'          => optional($meal->tallies)->times_offered,
            'total_ordered'          => optional($meal->tallies)->total_ordered,
            'total_servings'         => optional($meal->tallies)->total_servings,
            'last_offered'           => DatePresenter::pretty($recent_usage),
            'last_offered_ago'       => $recent_usage ? $recent_usage->diffForHumans() : 'Never used',
        ];
    }

    private static function getMostRecentUsedDate(?Carbon $last_used, ?MealTally $tally)
    {
        if(!$last_used && !$last_used) {
            return null;
        }

        if(!$last_used && $tally) {
            return $tally->last_offered;
        }

        if($last_used && !$tally) {
            return $last_used;
        }

        return $last_used->gt($tally->last_offered) ? $last_used : $tally->last_offered;
    }

    public static function forPublic(Meal $meal): array
    {
        $gallery = static::getGallery($meal);
        $title_image = $gallery->count() ? $gallery->first() : $meal->defaultImage();

        return [
            'id'              => $meal->id,
            'unique_id'       => $meal->unique_id,
            'name'            => $meal->name,
            'description'     => $meal->description,
            'allergens'       => $meal->allergens,
            'prep_time'       => $meal->prep_time,
            'cook_time'       => $meal->cook_time,
            'instructions'    => $meal->instructions,
            'serving_energy'  => $meal->serving_energy,
            'serving_carbs'   => $meal->serving_carbs,
            'serving_fat'     => $meal->serving_fat,
            'serving_protein' => $meal->serving_protein,
            'ingredients'     => $meal->ingredients()->orderBy('position')->get()->map->toArray()->all(),
            'title_image'     => $title_image['web'],
            'thumb_img'       => $title_image['thumb'],
            'gallery'         => $gallery->all(),
            'classifications' => $meal->classifications->map->toArray()->all(),
        ];
    }

    public static function forRecipeCard(Meal $meal): array
    {
        $gallery = static::getGallery($meal);
        $title_image = $gallery->count() ? $gallery->first() : $meal->defaultImage();

        return [
            'name'            => $meal->name,
            'description'     => $meal->description,
            'prep_time'       => $meal->prep_time,
            'cook_time'       => $meal->cook_time,
            'instructions'    => $meal->instructions,
            'required'        => $meal->ingredients()->orderBy('position')->get()->map->toArray()->reject(fn($i
            ) => $i['in_kit'])->all(),
            'ingredients'     => $meal->recipeCardIngredients(),
            'title_image'     => $title_image['web'],
            'thumb_img'       => $title_image['thumb'],
            'classifications' => $meal->classifications->map->toArray()->all(),
        ];
    }

    private static function getGallery(Meal $meal)
    {
        return $meal->getMedia(Meal::GALLERY)
                    ->sortBy(fn($m) => $m->getCustomProperty('position'))
                    ->map(fn($m) => [
                        'id'    => $m->id,
                        'thumb' => $m->getUrl('thumb'),
                        'web'   => $m->getUrl('web'),
                        'src'   => $m->getUrl('web'),
                    ])->values();
    }
}
