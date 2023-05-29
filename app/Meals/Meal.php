<?php

namespace App\Meals;

use App\HasNotes;
use App\Loggable;
use App\LogsActivities;
use App\Orders\MealTally;
use App\Orders\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Meal extends Model implements HasMedia, Loggable
{
    use InteractsWithMedia, HasNotes, LogsActivities;

    const GALLERY = 'gallery';
    const DEFAULT_IMAGE = '/images/logos/tastebox_logo.jpg';

    const SERVING_PRICE = 95;

    protected $fillable = [
        'unique_id',
        'name',
        'description',
        'allergens',
        'prep_time',
        'cook_time',
        'instructions',
        'serving_energy',
        'serving_carbs',
        'serving_fat',
        'serving_protein',
        'public_recipe_notes',
        'price_tier',
    ];

    protected $casts = [
        'unique_id'       => 'string',
        'is_public'       => 'boolean',
        'prep_time'       => 'integer',
        'cook_time'       => 'integer',
        'serving_carbs'   => 'integer',
        'serving_fat'     => 'integer',
        'serving_protein' => 'integer',
        'serving_energy'  => 'integer',
        'price_tier'      => MealPriceTier::class,
    ];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
    }

    public function latestMenus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class)->orderByDesc('id');
    }

    public static function createNew($attributes = [], $classifications = []): self
    {
        $meal = static::create(array_merge($attributes, [
            'unique_id' => static::generateUniqueId(),
        ]));
        $meal->assignClassifications($classifications);

        return $meal;
    }

    public static function copy(Meal $meal, string $name)
    {
        $copy = self::createNew([
            'name'            => $name,
            'description'     => $meal->description,
            'allergens'       => $meal->allergens,
            'prep_time'       => $meal->prep_time,
            'cook_time'       => $meal->cook_time,
            'instructions'    => $meal->instructions,
            'serving_energy'  => $meal->serving_energy,
            'serving_carbs'   => $meal->serving_carbs,
            'serving_fat'     => $meal->serving_fat,
            'serving_protein' => $meal->serving_protein,
        ]);
        $copy->retract();

        collect($meal->ingredients->toArray())
            ->each(fn($ingredient) => $copy->ingredients()->attach($ingredient['id'], [
                'position' => $ingredient['position'],
                'in_kit'   => $ingredient['in_kit'],
                'bundled'  => $ingredient['bundled'],
                'form'     => $ingredient['form'],
                'quantity' => $ingredient['quantity'],
            ]));


        return $copy;
    }

    public function updateWithFormData($form_data)
    {
        $this->update($form_data['meal_attributes']);
        $this->assignClassifications($form_data['classifications']);
    }

    public function setNutritionalInfo(NutritionalInfo $nutritionalInfo)
    {
        $this->update($nutritionalInfo->toArray());
    }

    public function setIngredients(IngredientList $ingredientsList)
    {
        $this->ingredients()->detach();
        collect($ingredientsList->ingredients)
            ->each(fn($ing) => $this->ingredients()->attach($ing['id'], [
                'quantity' => $ing['quantity'],
                'in_kit'   => $ing['in_kit'],
                'form'     => $ing['form'],
                'group'    => $ing['group'],
                'bundled'  => $ing['bundled'] ?? false,
            ]));
    }

    public function setInstructions(string $instructions = '')
    {
        $this->update(['instructions' => $instructions]);
    }

    public function safeDelete()
    {
        $this->ingredients()->sync([]);
        $this->delete();
    }

    public static function generateUniqueId()
    {
        return Str::of(Str::random(10))
                  ->lower()
                  ->replaceMatches('/[^a-z]{1}/', "")
                  ->substr(0, 6);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->orderBy('position')
                    ->using(MealIngredient::class)
                    ->withPivot(['id', 'quantity', 'in_kit', 'position', 'group', 'form', 'bundled']);
    }

    public function organizeIngredients(array $ingredientData)
    {
        collect($ingredientData)
            ->each(function ($info) {
                $meal_ingredient = MealIngredient::find($info['meal_ingredient_id']);
                $meal_ingredient->update([
                    'position' => $info['position'],
                    'group'    => $info['group'],
                    'bundled'  => $info['bundled'] ?? false,
                ]);

            });
    }

    public function customerIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->wherePivot('in_kit', 0);
    }

    public function kitIngredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->wherePivot('in_kit', 1);
    }

    public function recipeCardIngredients(): array
    {
        $ingredients = $this
            ->ingredients()
            ->orderBy('position')
            ->get()
            ->map->toArray()
                 ->filter(fn($i) => $i['in_kit'])
                 ->reduce(function ($list, $ingredient) {
                     if ($ingredient['bundled']) {
                         if (!$list->contains(fn($i) => $i['description'] === $ingredient['group'])) {
                             $ingredient['description'] = $ingredient['group'];
                             $ingredient['group'] = 'main';
                             $list->push($ingredient);

                             return $list;
                         } else {
                             return $list;
                         }

                     }
                     $list->push($ingredient);

                     return $list;
                 }, collect([]));

        return $ingredients->groupBy(fn($i) => $i['group'])
                           ->mapWithKeys(function ($ingredients, $group_name) {
                               return [
                                   $group_name => $ingredients->map(
                                       fn($i) => $i['description'])->values()->all()
                               ];
                           })->all();


    }

    public function addImage(UploadedFile $file)
    {
        return $this->addMedia($file)
                    ->usingFileName($file->hashName())
                    ->withCustomProperties(['position' => 999])
                    ->preservingOriginal()
                    ->toMediaCollection(static::GALLERY);
    }

    public function setGalleryPositions(array $ids)
    {
        collect($ids)->each(fn($id, $index) => $this->setMediaItemPosition(Media::find($id), $index + 1));
    }

    private function setMediaItemPosition(Media $media, $position)
    {
        $media->setCustomProperty('position', $position);
        $media->save();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 600, 400)
             ->optimize()
             ->performOnCollections(self::GALLERY);

        $this->addMediaConversion('web')
             ->fit(Manipulations::FIT_CROP, 1200, 800)
             ->optimize()
             ->performOnCollections(self::GALLERY);
    }

    public function asArrayForAdmin()
    {
        return MealsPresenter::forAdmin($this);
    }

    public function titleImage($conversion = '')
    {
        $image = $this->titleMedia();

        return $image ? $image->getUrl($conversion) : '/images/logos/tastebox_logo.jpg';
    }

    public function titleMedia(): ?Media
    {
        return $this->getMedia(static::GALLERY)
                    ->sortBy(fn($m) => $m->getCustomProperty('position'))
                    ->first();
    }

    public function defaultImage()
    {
        return [
            'id'    => null,
            'thumb' => static::DEFAULT_IMAGE,
            'web'   => static::DEFAULT_IMAGE,
            'src'   => static::DEFAULT_IMAGE,
        ];
    }

    public function classifications()
    {
        return $this->belongsToMany(Classification::class);
    }

    public function assignClassifications(array $ids)
    {
        return $this->classifications()->sync($ids);
    }

    public function publish()
    {
        $this->is_public = true;
        $this->save();
    }

    public function retract()
    {
        $this->is_public = false;
        $this->save();
    }

    public function createRecipeCard(): string
    {
        return RecipeCard::forMeal($this);

    }

    public function tallies()
    {
        return $this->hasOne(MealTally::class);
    }

    public function asRecipe(): array
    {
        $this->load('ingredients', 'classifications');

        return [
            'slug'         => $this->unique_id,
            'image'        => $this->titleImage('web'),
            'name'         => $this->name,
            'description'  => $this->description,
            'ingredients'  => $this->ingredients,
            'instructions' => $this->instructions,
            'public_notes' => $this->public_recipe_notes,
            'cooking_time' => ($this->cook_time + $this->prep_time) . "mins",
            'categories'   => $this->classifications,
        ];
    }

    public function getActivityItemUrl(): string
    {
        return "/meals/{$this->id}/manage/info";
    }

    public function logCreateActivity($name)
    {
        $action = sprintf("%s created a new meal '%s'", $name, $this->name);
        $this->logActivity($name, $action, $this->getActivityItemUrl());
    }

    public function logPublishActivity($name)
    {
        $action = sprintf("%s published '%s'", $name, $this->name);
        $this->logActivity($name, $action, $this->getActivityItemUrl());
    }
}
