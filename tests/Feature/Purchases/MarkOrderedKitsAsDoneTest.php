<?php


namespace Tests\Feature\Purchases;


use App\Purchases\OrderedKit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class MarkOrderedKitsAsDoneTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function mark_batch_of_ordered_kits_as_done()
    {
        $this->withoutExceptionHandling();

        $ordered_kitA = factory(OrderedKit::class)->state('due')->create();
        $ordered_kitB = factory(OrderedKit::class)->state('due')->create();
        $ordered_kitC = factory(OrderedKit::class)->state('due')->create();

        $response = $this->asAdmin()->postJson("/admin/api/completed-ordered-kits", [
            'ordered_kit_ids' => [$ordered_kitA->id, $ordered_kitB->id, $ordered_kitC->id],
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('ordered_kits', [
            'id'     => $ordered_kitA->id,
            'status' => OrderedKit::STATUS_DONE,
        ]);

        $this->assertDatabaseHas('ordered_kits', [
            'id'     => $ordered_kitB->id,
            'status' => OrderedKit::STATUS_DONE,
        ]);

        $this->assertDatabaseHas('ordered_kits', [
            'id'     => $ordered_kitC->id,
            'status' => OrderedKit::STATUS_DONE,
        ]);
    }

    /**
     *@test
     */
    public function the_ordered_kit_ids_are_required()
    {
        $this->assertFieldIsInvalid(['ordered_kit_ids' => null]);
    }

    /**
     *@test
     */
    public function the_ordered_kit_ids_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['ordered_kit_ids' => 'not-an-array']);
    }

    /**
     *@test
     */
    public function each_ordered_kit_id_must_exists_in_ordered_kits_table()
    {
        $this->assertNull(OrderedKit::find(99));

        $this->assertFieldIsInvalid(['ordered_kit_ids' => [99]], 'ordered_kit_ids.0');
    }

    private function assertFieldIsInvalid($field, $error_key = null)
    {
        $response = $this->asAdmin()->postJson("/admin/api/completed-ordered-kits", $field);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($error_key ?? array_key_first($field));
    }
}
