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
        $response->assertRedirect("/my-kits/{$kit->id}");
    }

    /**
     *@test
     */
    public function the_menu_id_is_must_exist_in_menu_table()
    {
        $response = $this->from("/")->asGuest()->post('/my-kits', ['menu_id' => 999]);
        $response->assertRedirect("/");
        $response->assertSessionHasErrors('menu_id');
    }
}
