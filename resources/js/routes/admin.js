import MealsIndex from "../vue/Pages/Meals/MealsIndex";
import MealShow from "../vue/Pages/Meals/MealShow";
import MealEdit from "../vue/Pages/Meals/MealEdit";
import MealGallery from "../vue/Pages/Meals/MealGallery";
import MenuIndex from "../vue/Pages/Menu/MenuIndex";
import MenuShow from "../vue/Pages/Menu/MenuShow";
import MenuEditMeals from "../vue/Pages/Menu/MenuEditMeals";
import RecentOrders from "../vue/Pages/Orders/RecentOrders";
import Order from "../vue/Pages/Orders/Order";
import CurrentBatch from "../vue/Pages/Batches/CurrentBatch";
import BatchKits from "../vue/Components/Batches/BatchKits";
import BatchMeals from "../vue/Components/Batches/BatchMeals";
import BatchIngredients from "../vue/Components/Batches/BatchIngredients";
import BatchSummary from "../vue/Pages/Batches/BatchSummary";

export default [
    { path: "/meals", component: MealsIndex },
    { path: "/meals/:id", component: MealShow },
    { path: "/meals/:id/edit", component: MealEdit },
    { path: "/meals/:id/gallery", component: MealGallery },
    { path: "/menus", component: MenuIndex },
    { path: "/menus/:id", component: MenuShow },
    { path: "/menus/:id/edit-meals", component: MenuEditMeals },
    { path: "/recent-orders", component: RecentOrders },
    { path: "/recent-orders/:id", component: Order },
    {
        path: "/current-batch",
        component: CurrentBatch,
        children: [
            { path: "/", component: BatchSummary },
            { path: "kits", component: BatchKits },
            { path: "meals", component: BatchMeals },
            { path: "ingredients", component: BatchIngredients },
        ],
    },
];
