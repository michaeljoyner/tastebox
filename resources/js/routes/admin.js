import MealsIndex from "../vue/Pages/Meals/MealsIndex";
import MealShow from "../vue/Pages/Meals/MealShow";
import MealEdit from "../vue/Pages/Meals/MealEdit";
import MealGallery from "../vue/Pages/Meals/MealGallery";
import MenuIndex from "../vue/Pages/Menu/MenuIndex";
import MenuShow from "../vue/Pages/Menu/MenuShow";

export default [
    { path: "/meals", component: MealsIndex },
    { path: "/meals/:id", component: MealShow },
    { path: "/meals/:id/edit", component: MealEdit },
    { path: "/meals/:id/gallery", component: MealGallery },
    { path: "/menus", component: MenuIndex },
    { path: "/menus/:id", component: MenuShow },
];
