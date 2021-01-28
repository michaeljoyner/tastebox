<?php

namespace Tests\Unit\ShoppingList;

use App\Purchases\ShoppingList;
use App\Purchases\ShoppingListItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_new_shopping_list_is_empty()
    {
        $list = new ShoppingList();

        $this->assertSame([], $list->toArray());
    }

    /**
     *@test
     */
    public function can_add_an_item_to_the_list()
    {
        $list = new ShoppingList();

        $item = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '100g',
            'form' => 'test form',
            'meal' => 'test meal',
            'servings' => 10
        ]);

        $list->addItem($item);

        $expected = [
            [
                'id' => 1,
                'item_name' => 'test description',
                'amounts' => [
                    'g' => 250.0,
                ],
                'uses' => [
                    '10 servings of test meal (100g test form)'
                ],
            ]
        ];

        $this->assertSame($expected, $list->toArray());
    }

    /**
     *@test
     */
    public function can_add_a_second_item_of_same_ingredient_and_unit()
    {
        $list = new ShoppingList();

        $itemA = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '100g',
            'form' => 'test form',
            'meal' => 'test meal',
            'servings' => 10
        ]);

        $itemB = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '600g',
            'form' => 'other form',
            'meal' => 'other meal',
            'servings' => 8
        ]);

        $list->addItem($itemA);
        $list->addItem($itemB);

        $expected = [
            [
                'id' => 1,
                'item_name' => 'test description',
                'amounts' => [
                    'g' => 250.0 + 1200,
                ],
                'uses' => [
                    '10 servings of test meal (100g test form)',
                    '8 servings of other meal (600g other form)',
                ],
            ]
        ];

        $this->assertSame($expected, $list->toArray());
    }

    /**
     *@test
     */
    public function units_are_considered_to_be_case_insensitive()
    {
        $list = new ShoppingList();

        $itemA = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '100g',
            'form' => 'test form',
            'meal' => 'test meal',
            'servings' => 10
        ]);

        $itemB = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '600G',
            'form' => 'other form',
            'meal' => 'other meal',
            'servings' => 8
        ]);

        $list->addItem($itemA);
        $list->addItem($itemB);

        $expected = [
            [
                'id' => 1,
                'item_name' => 'test description',
                'amounts' => [
                    'g' => 250.0 + 1200,
                ],
                'uses' => [
                    '10 servings of test meal (100g test form)',
                    '8 servings of other meal (600G other form)',
                ],
            ]
        ];

        $this->assertSame($expected, $list->toArray());
    }

    /**
     *@test
     */
    public function can_add_a_second_same_ingredient_with_different_unit()
    {
        $list = new ShoppingList();

        $itemA = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '100g',
            'form' => 'test form',
            'meal' => 'test meal',
            'servings' => 10
        ]);

        $itemB = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '6',
            'form' => 'other form',
            'meal' => 'other meal',
            'servings' => 3
        ]);

        $list->addItem($itemA);
        $list->addItem($itemB);

        $expected = [
            [
                'id' => 1,
                'item_name' => 'test description',
                'amounts' => [
                    'g' => 250.0,
                    'x_unit' => 4.5
                ],
                'uses' => [
                    '10 servings of test meal (100g test form)',
                    '3 servings of other meal (6 other form)',
                ],
            ]
        ];

        $this->assertSame($expected, $list->toArray());
    }

    /**
     *@test
     */
    public function can_add_a_completely_new_item()
    {
        $list = new ShoppingList();

        $itemA = new ShoppingListItem([
            'id' => 1,
            'description' => 'test description',
            'quantity' => '100g',
            'form' => 'test form',
            'meal' => 'test meal',
            'servings' => 10
        ]);

        $itemB = new ShoppingListItem([
            'id' => 2,
            'description' => 'other description',
            'quantity' => '6',
            'form' => 'other form',
            'meal' => 'other meal',
            'servings' => 3
        ]);

        $list->addItem($itemA);
        $list->addItem($itemB);

        $expected = [
            [
                'id' => 1,
                'item_name' => 'test description',
                'amounts' => [
                    'g' => 250.0,
                ],
                'uses' => [
                    '10 servings of test meal (100g test form)',
                ],
            ],
            [
                'id' => 2,
                'item_name' => 'other description',
                'amounts' => [
                    'x_unit' => 4.5
                ],
                'uses' => [
                    '3 servings of other meal (6 other form)',
                ],
            ]
        ];

        $this->assertSame($expected, $list->toArray());
    }
}
