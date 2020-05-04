<?php


namespace Tests\Unit\Meals;


use App\Http\Requests\MealFormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealFormRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_presents_the_formatted_data()
    {
        $request = new MealFormRequest([
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
            'ingredients'     => [
                ['id' => 1, 'quantity' => '2', 'in_kit' => false],
                ['id' => 2, 'quantity' => '3 tsp', 'in_kit' => false],
                ['id' => 3, 'quantity' => '1 bag', 'in_kit' => true],
                ['id' => 4, 'quantity' => '', 'in_kit' => true],
                ['id' => 5, 'quantity' => null, 'in_kit' => true],
            ],
        ]);


        $expected = [
            'meal_attributes' => [
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
            ],
            'ingredients'     => [
                1 => ['quantity' => '2', 'in_kit' => false],
                2 => ['quantity' => '3 tsp', 'in_kit' => false],
                3 => ['quantity' => '1 bag', 'in_kit' => true],
                4 => ['quantity' => null, 'in_kit' => true],
                5 => ['quantity' => null, 'in_kit' => true],
            ],
        ];

        $this->assertEquals($expected, $request->formData());
    }
}
