<?php

namespace App\Meals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Meal extends Model implements HasMedia
{
    use InteractsWithMedia;

    const GALLERY = 'gallery';
    const DEFAULT_IMAGE = '/images/logos/tastebox_logo.jpg';

    const SERVING_PRICE = 75;

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
        'serving_protein'
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
    ];

    public static function createNew($attributes = [], $classifications = []): self
    {
        $meal =  static::create(array_merge($attributes, [
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
        $copy->ingredients()->sync(
            collect($meal->ingredients->toArray())
                ->mapWithKeys(fn($ing) => [$ing['id'] => ['in_kit' => $ing['in_kit'], 'quantity' => $ing['quantity']]])
        );

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
            ->each(fn ($ing) => $this->ingredients()->attach($ing['id'], [
                'quantity' => $ing['quantity'],
                'in_kit' => $ing['in_kit'],
                'form' => $ing['form'],
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
                    ->using(MealIngredient::class)
                    ->withPivot(['quantity', 'in_kit', 'position', 'group', 'form']);
    }

    public function organizeIngredients(array $ingredientData)
    {
        collect($ingredientData)
            ->each(function($info) {
               $this->ingredients()->updateExistingPivot($info['id'], [
                   'position' => $info['position'],
                   'group' => $info['group'],
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
}
