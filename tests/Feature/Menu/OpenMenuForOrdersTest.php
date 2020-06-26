<?php


namespace Tests\Feature\Menu;


use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenMenuForOrdersTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function mark_menu_as_open_for_orders()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/orderable-menus", [
            'menu_id' => $menu->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'can_order' => true,
        ]);
    }
}
