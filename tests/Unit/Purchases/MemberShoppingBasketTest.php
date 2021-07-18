<?php


namespace Tests\Unit\Purchases;


use App\Meals\Meal;
use App\Orders\Menu;
use App\Purchases\ShoppingBasket;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MemberShoppingBasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_kit_to_members_shopping_basket()
    {
        $member = factory(User::class)->state('member')->create();
        $basket = ShoppingBasket::for($member);
        $menu = factory(Menu::class)->create();

        $kit = $basket->addKit($menu->id);

        $this->assertEquals($menu->id, $kit->menu_id);
        $this->assertTrue(Str::isUuid($kit->id));


        $this->assertDatabaseHas('member_shopping_baskets', [
            'user_id' => $member->id,
            'contents' => serialize([$kit])
        ]);
    }

    /**
     *@test
     */
    public function can_retrieve_a_basket_with_content_for_member()
    {
        $basket = ShoppingBasket::for(null);
        $menu = factory(Menu::class)->create();
        $mealA = factory(Meal::class)->create();
        $mealB = factory(Meal::class)->create();
        $menu->setMeals([$mealA->id, $mealB->id]);
        $kit = $basket->addKit($menu->id);
        $kit->setMeal($mealA->id, 2);
        $kit->setMeal($mealB->id, 2);

        $member = factory(User::class)->state('member')->create();

        $member->shoppingBasket()->create(['contents' => serialize($basket->kits->all())]);

        $new_basket = ShoppingBasket::for($member);


        $this->assertSame($basket->kits->count(), $new_basket->kits->count());
        $this->assertSame($basket->kits->first()->id, $new_basket->kits->first()->id);
        $this->assertEquals($basket->kits->first()->mealSummary(), $new_basket->kits->first()->mealSummary());
        $this->assertEquals($basket->kits->first()->price(), $new_basket->kits->first()->price());
    }
}
