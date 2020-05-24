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
        'unique_id' => 'string',
        'is_public' => 'boolean'
    ];

    public static function createNew()
    {
        return static::create([
            'unique_id' => static::generateUniqueId(),
        ]);
    }

    public function updateWithFormData($form_data)
    {
        $this->update($form_data['meal_attributes']);
        $this->ingredients()->sync($form_data['ingredients']);
        $this->assignClassifications($form_data['classifications']);
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
                    ->withPivot(['quantity', 'in_kit']);
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
                    ->usingFileName(Str::slug(Str::random(10)))
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
             ->fit(Manipulations::FIT_CROP, 300, 200)
             ->optimize()
             ->performOnCollections(self::GALLERY);

        $this->addMediaConversion('web')
             ->fit(Manipulations::FIT_CROP, 1200, 800)
             ->optimize()
             ->performOnCollections(self::GALLERY);
    }

    public function asArrayForAdmin()
    {
        $gallery = $this->getMedia(static::GALLERY)
                        ->sortBy(fn($m) => $m->getCustomProperty('position'))
                        ->map(fn($m) => [
                            'id'    => $m->id,
                            'thumb' => $m->getUrl('thumb'),
                            'web'   => $m->getUrl('web'),
                            'src'   => $m->getUrl('web'),
                        ])->values();;

        return [
            'id'              => $this->id,
            'unique_id'       => $this->unique_id,
            'is_public'       => $this->is_public,
            'name'            => $this->name,
            'description'     => $this->description,
            'allergens'       => $this->allergens,
            'prep_time'       => $this->prep_time,
            'cook_time'       => $this->cook_time,
            'instructions'    => $this->instructions,
            'serving_energy'  => $this->serving_energy,
            'serving_carbs'   => $this->serving_carbs,
            'serving_fat'     => $this->serving_fat,
            'serving_protein' => $this->serving_protein,
            'ingredients'     => $this->ingredients->map->toArray()->all(),
            'title_image'     => $gallery->count() ? $gallery->first() : $this->defaultImage(),
            'gallery'         => $gallery->all(),
            'classifications' => $this->classifications->map->toArray()->all(),
        ];
    }

    private function defaultImage()
    {
        return [
            'id'    => null,
            'thumb' => '/images/logos/tastebox_logo.jpg',
            'web'   => '/images/logos/tastebox_logo.jpg',
            'src'   => '/images/logos/tastebox_logo.jpg',
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
