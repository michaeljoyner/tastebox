import BasketsIndex from "../vue/Pages/BasketsIndex";
import MealsIndex from "../vue/Pages/Meals/MealsIndex";
import MealShow from "../vue/Pages/Meals/MealShow";
import MealEdit from "../vue/Pages/Meals/MealEdit";
import MealGallery from "../vue/Pages/Meals/MealGallery";

export default [
    { path: "/meals", component: MealsIndex },
    { path: "/meals/:id", component: MealShow },
    { path: "/meals/:id/edit", component: MealEdit },
    { path: "/meals/:id/gallery", component: MealGallery },
    { path: "/baskets", component: BasketsIndex },
];
