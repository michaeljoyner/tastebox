<?php


namespace App\Meals;


use App\Orders\Menu;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class RecipeCard
{
    const DISK_NAME = 'recipes';

    public static function disk(): Filesystem
    {
        return Storage::disk(static::DISK_NAME);
    }

    public static function clearDisk()
    {
        static::disk()->delete(static::disk()->allFiles());
    }

    public static function forMeal(Meal $meal): string
    {
        $html = view('recipes.card', ['meal' => MealsPresenter::forRecipeCard($meal)])->render();

        $name = sprintf("%s.pdf", Str::slug($meal->name));

        Browsershot::html($html)->waitUntilNetworkIdle()
                   ->setNodeBinary(config('browsershot.node_path'))
                   ->setNpmBinary(config('browsershot.npm_path'))
                   ->format('A4')
                   ->landscape()
                   ->margins(0, 0, 0, 0)
                   ->save(static::disk()->path($name));

        return $name;
    }

    public static function archiveForMenu(Menu $menu): string
    {
        $archive_name = sprintf("menu_%s_recipes.zip", $menu->weekOfYear());

        $zip = new \ZipArchive();

        $zip->open(static::disk()->path($archive_name), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $menu->meals->map(fn(Meal $meal) => $meal->createRecipeCard())
                    ->each(fn($file) => $zip->addFile(static::disk()->path($file), $file));
        $zip->close();

        return $archive_name;
    }
}
