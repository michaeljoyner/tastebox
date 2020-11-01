<?php

namespace tests\Unit\Meals;

use App\Meals\Classification;
use App\Meals\Ingredient;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PresentMealTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function present_as_array_for_admin_use()
    {
        $ingredientA = factory(Ingredient::class)->create();
        $ingredientB = factory(Ingredient::class)->create();
        $ingredientC = factory(Ingredient::class)->create();
        $ingredientD = factory(Ingredient::class)->create();
        $ingredientE = factory(Ingredient::class)->create();

        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();

        $meal = factory(Meal::class)->state('public')->create([
            'name'            => 'test name',
            'description'     => 'test description',
            'allergens'       => 'test allergens',
            'prep_time'       => 100,
            'cook_time'       => 250,
            'instructions'    => 'test instructions',
            'serving_energy'  => '150',
            'serving_carbs'   => 50,
            'serving_fat'     => 70,
            'serving_protein' => 0,
        ]);

        $meal->ingredients()->sync([
            $ingredientA->id => ['quantity' => '2', 'in_kit' => false],
            $ingredientB->id => ['quantity' => '3 tsp', 'in_kit' => false],
            $ingredientC->id => ['quantity' => '1 bag', 'in_kit' => true],
            $ingredientD->id => ['quantity' => null, 'in_kit' => true],
            $ingredientE->id => ['quantity' => null, 'in_kit' => true],
        ]);

        $meal->assignClassifications([$classificationA->id, $classificationB->id]);

        $image = $meal->addImage(UploadedFile::fake()->image('testpic.png'));

        $expected = [
            'id'              => $meal->id,
            'unique_id'       => $meal->unique_id,
            'is_public'       => true,
            'name'            => 'test name',
            'description'     => 'test description',
            'allergens'       => 'test allergens',
            'prep_time'       => 100,
            'cook_time'       => 250,
            'instructions'    => 'test instructions',
            'serving_energy'  => 150,
            'serving_carbs'   => 50,
            'serving_fat'     => 70,
            'serving_protein' => 0,
            'ingredients'     => [
                [
                    'id'          => $ingredientA->id,
                    'description' => $ingredientA->description,
                    'quantity'    => '2',
                    'in_kit'      => false
                ],
                [
                    'id'          => $ingredientB->id,
                    'description' => $ingredientB->description,
                    'quantity'    => '3 tsp',
                    'in_kit'      => false
                ],
                [
                    'id'          => $ingredientC->id,
                    'description' => $ingredientC->description,
                    'quantity'    => '1 bag',
                    'in_kit'      => true
                ],
                [
                    'id'          => $ingredientD->id,
                    'description' => $ingredientD->description,
                    'quantity'    => null,
                    'in_kit'      => true
                ],
                [
                    'id'          => $ingredientE->id,
                    'description' => $ingredientE->description,
                    'quantity'    => null,
                    'in_kit'      => true
                ],
            ],
            'title_image'     => [
                'id'    => $image->id,
                'thumb' => $image->getUrl('thumb'),
                'web'   => $image->getUrl('web'),
                'src'   => $image->getUrl('web'),
            ],
            'gallery'         => [
                [
                    'id'    => $image->id,
                    'thumb' => $image->getUrl('thumb'),
                    'web'   => $image->getUrl('web'),
                    'src'   => $image->getUrl('web'),
                ]
            ],
            'classifications' => [
                [
                    'id'   => $classificationA->id,
                    'name' => $classificationA->name,
                ],
                [
                    'id'   => $classificationB->id,
                    'name' => $classificationB->name,
                ]
            ],
            'last_touched_timestamp' => now()->timestamp,

        ];

        $this->assertEquals($expected, $meal->fresh()->asArrayForAdmin());
    }
}
