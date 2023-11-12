<?php

namespace App\Http\Controllers;

use App\Orders\Menu;
use Dymantic\InstagramFeed\Profile;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show()
    {
        $menus = Menu::available()->with('meals')->orderBy('current_from')->get();
        $current = $menus->first(fn (Menu $menu) => $menu->isCurrent());
        $instagrams = Profile::for('tastebox')->feed();

        $testes = [
            'TasteBox is super <strong>convenient</strong> if you\'re a person who has very little time and/or energy left to cook after a long day of work. It\'s <strong>affordable</strong>, you get a great variety of dishes, and it ensures you have balanced meals - especially your vegetables!',
            "Definitely <strong>saves time and money</strong> as all ingredients are used . Variety yes and trying new dishes. I cook for two , work full time and find that hubby and I spend time over a beer/glass of wine while cooking together 🥳",
//            "My wife and I both work and have a 16yr old daughter. It has been an absolute pleasure dealing with Steph and her team. Not only does it <strong>save us money</strong> (we have no wastage) it takes the effort out of preparing meals every night. The bonus is that we know we are getting well thought out and <strong>nutritious meals</strong> that are delicious. So simple and easy, it makes cooking supper so much more enjoyable rather than a chore .",
//            "I absolutely love cooking with TasteBox. I don’t enjoy looking through recipe books so my day to day cooking is very boring. I love the fact that I am trying out different foods that I would never venture to cook. I don’t end up throwing food away and I’m not buying bottles of ingredients that I’m only going to use small amounts of. The meals are quick and the recipes uncomplicated to follow. Often meals can be cooked in one pot. I like to order a few weeks at a time and the website is easy to navigate. TasteBox has <strong>revolutionized my cooking</strong> 🥳",
            "My wife and I both work and have a 16yr old daughter. It has been an absolute pleasure dealing with Steph and her team. Not only does it <strong>save us money</strong> (we have no wastage) it takes the effort out of preparing meals every night.",
            "We know we are getting well thought out and <strong>nutritious meals</strong> that are delicious. So simple and easy, it makes cooking supper so much more enjoyable rather than a chore .",
            "I absolutely <strong>love cooking with TasteBox</strong>. I don’t enjoy looking through recipe books so my day to day cooking is very boring. I love the fact that I am trying out different foods that I would never venture to cook. I don’t end up throwing food away and I’m not buying bottles of ingredients that I’m only going to use small amounts of.",
            "The meals are quick and the recipes uncomplicated to follow. Often meals can be cooked in one pot. I like to order a few weeks at a time and the website is easy to navigate. TasteBox has <strong>revolutionized my cooking</strong> 🥳",
        ];


        if(!$current) {
            $current = $menus->shift();
        }
        return view('front.home.page', [
            'current' => optional($current)->presentForPublic(),
            'menus' => $menus->reject(fn (Menu $menu) => $menu->is($current))->map->presentForPublic(),
            'instagrams' => $instagrams->collect()->map->toArray()->take(8),
            'testes' => $testes,
        ]);
    }
}
