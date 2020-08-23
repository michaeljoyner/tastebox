<?php

namespace Tests\Feature\Purchases;

use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateKitTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_kit_for_the_shopping_basket()
    {
        $this->withoutExceptionHandling();
        $this->assertCount(0, session('basket.kits', []));

        $menu = factory(Menu::class)->create();

        $response = $this->asGuest()->post('/my-kits', ['menu_id' => $menu->id]);

        $this->assertCount(1, session('basket.kits'));

        $kit = session('basket.kits')[0];

        $this->assertEquals($menu->id, $kit->menu_id);
        $response->assertSuccessful();
    }

    /**
     *@test
     */
    public function the_menu_id_is_must_exist_in_menu_table()
    {
        $response = $this->from("/")->asGuest()->postJson('/my-kits', ['menu_id' => 999]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('menu_id');
    }
}
