<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function show()
    {
        $faqs = [
            [
                'question' => 'Do I need any cooking experience?',
                'answer' => 'Not at all. Our meals are not complicated at all and every kit is accompanied with an easy-to-follow recipe card that has been written with beginners in mind.',
            ],
            [
                'question' => 'What ingredients and equipment do I need to have at home?',
                'answer' => 'When we design our meals, we always envision someone with the most basic cooking equipment – we avoid the situation of you requiring any fancy cooking or food preparation equipment. In terms of ingredients, mostly all you will need is salt, pepper and cooking oil. We sometimes require you to add some milk or perhaps a pinch of sugar.',
            ],
            [
                'question' => 'What type of cooking oil do you suggest I use?',
                'answer' => 'Olive oil is a great choice for making a salad dressing or sautéing something over a medium heat. However, it has a low smoking point, meaning that when you use it on a high heat, the beneficial compounds begin to degrade. Rather use canola, avocado or coconut oil for high heat cooking.',
            ],
            [
                'question' => 'I have dietary restrictions. Can I still order TasteBox?',
                'answer' => 'Potential allergens are listed next to each meal. Our ingredients are packed separately so it is possible to leave something out of a meal. Also, please feel free to contact dietician@tastebox.co.za to get more specific information. ',
            ],
            [
                'question' => 'What order should I use my meal kits in?',
                'answer' => 'When we pack the ingredients for your meals, we always judge which should be used first and which will last longer. Based on this we suggest the order in which you use your meal kits. Check the sticker on the brown bag in which your meal kit is packed. ',
            ],
            [
                'question' => 'If my meat has thawed by the time I receive my box, should I re-freeze it?',
                'answer' => 'We do not suggest refreezing any of your meat. Simply place your meat in the fridge and use it within the week of receiving it. Our meals are designed to last five days, which is why you can order up to five meals per week.',
            ],
        ];
        return view('front.faqs.page', ['faqs' => $faqs]);
    }
}
