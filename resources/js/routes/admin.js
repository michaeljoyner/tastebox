import MealsIndex from "../vue/Pages/MealsIndex";
import MealEdit from "../vue/Pages/MealEdit";
import BasketsIndex from "../vue/Pages/BasketsIndex";

export default [
    { path: "/meals", component: MealsIndex },
    { path: "/meals/:id/edit", component: MealEdit },
    { path: "/baskets", component: BasketsIndex },
];
