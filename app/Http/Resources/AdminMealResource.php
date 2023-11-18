<?php

namespace App\Http\Resources;

use App\DatePresenter;
use App\Meals\Meal;
use App\Orders\MealTally;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AdminMealResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $last_used = $this->whenLoaded(
            'latestMenus',
            $this->latestMenus->first()?->current_from,
            null
        );
        $recent_usage = self::getMostRecentUsedDate($last_used, $this->tallies);

        return [
            'id' => $this->id,
            'unique_id' => $this->unique_id,
            'name' => $this->name,
            'description' => $this->description,
            'meal_card_description' => $this->meal_card_description,
            'classifications' => ClassificationResource::collection($this->whenLoaded('classifications')),
            'title_image' => $this->when(
                $this->hasMedia(Meal::GALLERY),
                MediaResource::make($this->getFirstMedia(Meal::GALLERY)),
                Meal::DEFAULT_IMAGE,
            ),

            'is_public'              => $this->is_public,
            'allergens'              => $this->allergens,
            'prep_time'              => $this->prep_time,
            'cook_time'              => $this->cook_time,
            'instructions'           => $this->instructions,
            'serving_energy'         => $this->serving_energy,
            'serving_carbs'          => $this->serving_carbs,
            'serving_fat'            => $this->serving_fat,
            'serving_protein'        => $this->serving_protein,
            'ingredients'            => IngredientResource::collection(
                $this->whenLoaded('ingredients')
            ),
            'gallery'                => MediaResource::collection($this->getMedia(Meal::GALLERY)),
            'last_touched_timestamp' => max($this->created_at->timestamp, $this->updated_at->timestamp),
            'recent_inclusion'       => DatePresenter::pretty($last_used),
            'upcoming'               => $last_used?->isFuture() ? $last_used?->diffForHumans() : false,
            'times_offered'          => $this->tallies?->times_offered,
            'total_ordered'          => $this->tallies?->total_ordered,
            'total_servings'         => $this->tallies?->total_servings,
            'last_offered'           => DatePresenter::pretty($recent_usage),
            'last_offered_ago'       => $recent_usage ? $recent_usage->diffForHumans() : 'Never used',
            'notes'                  => NoteResource::collection(
                $this->whenLoaded('notes')
            ),
            'public_recipe_notes'    => $this->public_recipe_notes,
            'tier' => $this->price_tier?->description(),
            'tier_value' => $this->price_tier?->value,
            'price' => $this->price_tier?->price(),
            'costings' => CostingResource::collection($this->whenLoaded('costings'))
        ];
    }

    private function getMostRecentUsedDate(?Carbon $last_used, ?MealTally $tally)
    {
        if (!$last_used && !$last_used) {
            return null;
        }

        if (!$last_used && $tally) {
            return $tally->last_offered;
        }

        if ($last_used && !$tally) {
            return $last_used;
        }

        return $last_used->gt($tally->last_offered) ? $last_used : $tally->last_offered;
    }
}
