<?php


namespace Tests\Feature\Menu;


use App\Orders\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CloseMenuForOrdersTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function mark_a_menu_as_closed_for_orders()
    {
        $this->withoutExceptionHandling();

        $menu = factory(Menu::class)->state('open')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/orderable-menus/{$menu->id}");
        $response->assertSuccessful();

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'can_order' => false,
        ]);
    }
}
