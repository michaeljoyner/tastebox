<?php

namespace Tests\Feature\Menu;

use App\AddOns\AddOn;
use App\Orders\Menu;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AssignAddOnsToMenuTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function assign_addons_to_a_given_menu()
    {
        $menu = factory(Menu::class)->create();
        $addonA = factory(AddOn::class)->create();
        $addonB = factory(AddOn::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/addons", [
            'add_on_ids' => [$addonA->id, $addonB->id]
        ]);
        $response->assertSuccessful();

        $this->assertCount(2, $menu->addOns);

        $this->assertTrue($menu->addOns->contains($addonA));
        $this->assertTrue($menu->addOns->contains($addonB));
    }

    /**
     *@test
     */
    public function add_on_ids_must_be_present_as_array()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/addons", [
            'add_on_ids' => 'not an array'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *@test
     */
    public function each_add_on_id_must_exist_in_add_ons_table_as_id()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/menus/{$menu->id}/addons", [
            'add_on_ids' => [999]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
