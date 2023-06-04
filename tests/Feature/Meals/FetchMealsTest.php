<?php

namespace Tests\Feature\Meals;

use App\Meals\Classification;
use App\Meals\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchMealsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_paginated_list_of_meals()
    {
        factory(Meal::class)->times(50)->create();

        $response = $this->asAdmin()->getJson("/admin/api/meals");
        $response->assertSuccessful();

        $fetched_meals = $response->json("data");
        $this->assertCount(40, $fetched_meals);
    }

    /**
     *@test
     */
    public function can_fetch_with_search()
    {
        $mealA = factory(Meal::class)->create(['name' => 'Testy meal']);
        $mealB = factory(Meal::class)->create(['name' => 'a test meal']);
        $mealC = factory(Meal::class)->create(['name' => 'meal of testicles']);

        factory(Meal::class)->times(10)->create(['name' => 'other']);

        $response = $this->asAdmin()->getJson("/admin/api/meals?q=test");
        $response->assertSuccessful();

        $fetched_meals = $response->json("data");
        $this->assertCount(3, $fetched_meals);

        $fetched_ids = collect($fetched_meals)->pluck('id')->values();

        $this->assertTrue($fetched_ids->contains($mealA->id));
        $this->assertTrue($fetched_ids->contains($mealB->id));
        $this->assertTrue($fetched_ids->contains($mealC->id));
    }

    /**
     *@test
     */
    public function can_search_with_classification_filters()
    {
        $classificationA = factory(Classification::class)->create();
        $classificationB = factory(Classification::class)->create();
        $classificationC = factory(Classification::class)->create();

        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $mealC = factory(Meal::class)->create();

        $mealA->classifications()->sync([$classificationB->id]);
        $mealB->classifications()->sync([$classificationB->id, $classificationA->id]);
        $mealC->classifications()->sync([$classificationC->id, $classificationA->id]);

        $classifications_query = sprintf(
            "classifications=%s,%s",
            $classificationA->id,
            $classificationC->id,
        );

        $response = $this
            ->asAdmin()
            ->getJson("/admin/api/meals?{$classifications_query}");
        $response->assertSuccessful();

        $fetched_meals = $response->json("data");
        $this->assertCount(1, $fetched_meals);

        $this->assertSame($mealC->id, $fetched_meals[0]['id']);


    }
}
