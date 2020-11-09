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
            'classifications' => ['1', '2'],
        ]);


        $expected = [
            'meal_attributes' => [
                'name'        => 'test name',
                'description' => 'test description',
                'allergens'   => 'test allergens',
                'prep_time'   => 100,
                'cook_time'   => 250,
            ],
            'classifications' => [1, 2],
        ];

        $this->assertEquals($expected, $request->formData());
    }
}
