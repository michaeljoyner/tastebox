import MealsIndex from "../vue/Pages/Meals/MealsIndex";
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
import MealPageShell from "../vue/Pages/Meals/MealPageShell";
import ShowMealGeneralInfo from "../vue/Components/Meals/ShowMealGeneralInfo";
import EditMealGeneralInfo from "../vue/Components/Meals/EditMealGeneralInfo";
import SetMealIngredients from "../vue/Components/Meals/SetMealIngredients";
import ShowMealNutritionalInfo from "../vue/Components/Meals/ShowMealNutritionalInfo";
import EditNutritionalInfo from "../vue/Components/Meals/EditNutritionalInfo";
import ShowMealInstructions from "../vue/Components/Meals/ShowMealInstructions";
import EditMealInstructions from "../vue/Components/Meals/EditMealInstructions";
import MealActionsPage from "../vue/Pages/Meals/MealActionsPage";
import CreateMeal from "../vue/Components/Meals/CreateMeal";
import ShowMealIngredients from "../vue/Components/Meals/ShowMealIngredients";
import OrganiseIngredients from "../vue/Components/Meals/OrganiseIngredients";
import InstagramIndex from "../vue/Pages/Instagram/InstagramIndex";
import CreateDiscountCode from "../vue/Components/Discounts/CreateDiscountCode";
import DiscountsIndex from "../vue/Components/Discounts/DiscountsIndex";
import DiscountEdit from "../vue/Components/Discounts/DiscountEdit";
import MailingListIndex from "../vue/Pages/MailingList/MailingListIndex";
import MenuImages from "../vue/Pages/Menu/MenuImages";
import BatchShoppingList from "../vue/Components/Batches/BatchShoppingList";
import CreateManualOrder from "../vue/Components/Batches/CreateManualOrder";
import PostCreate from "../vue/Pages/Blog/PostCreate";

export default [
    { path: "/meals", component: MealsIndex },
    { path: "/meals/create", component: CreateMeal },
    {
        path: "/meals/:meal/manage",
        component: MealPageShell,
        children: [
            { path: "info", component: ShowMealGeneralInfo },
            { path: "info/edit", component: EditMealGeneralInfo },
            { path: "ingredients", component: ShowMealIngredients },
            { path: "ingredients/edit", component: SetMealIngredients },
            { path: "ingredients/organise", component: OrganiseIngredients },
            { path: "nutritional-info", component: ShowMealNutritionalInfo },
            { path: "nutritional-info/edit", component: EditNutritionalInfo },
            { path: "instructions", component: ShowMealInstructions },
            { path: "instructions/edit", component: EditMealInstructions },
            { path: "photos", component: MealGallery },
            { path: "actions", component: MealActionsPage },
        ],
    },
    { path: "/menus", component: MenuIndex },
    { path: "/menus/:id", component: MenuShow },
    { path: "/menus/:id/edit-meals", component: MenuEditMeals },
    { path: "/menus/:menu/images", component: MenuImages },
    { path: "/recent-orders", component: RecentOrders },
    { path: "/recent-orders/:id", component: Order },
    {
        path: "/current-batch",
        component: CurrentBatch,
        children: [
            { path: "", component: BatchSummary },
            { path: "kits", component: BatchKits },
            { path: "meals", component: BatchMeals },
            { path: "ingredients", component: BatchIngredients },
            { path: "shopping", component: BatchShoppingList },
            { path: "manual-order", component: CreateManualOrder },
        ],
    },
    { path: "/instagram", component: InstagramIndex },
    { path: "/discount-codes/create", component: CreateDiscountCode },
    { path: "/discount-codes", component: DiscountsIndex },
    { path: "/discount-codes/:code/edit", component: DiscountEdit },
    { path: "/mailing-list", component: MailingListIndex },

    { path: "/blog/create-post", component: PostCreate },
];
