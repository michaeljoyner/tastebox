import MealsIndex from "../vue/Pages/Meals/MealsIndex";
import MealGallery from "../vue/Pages/Meals/MealGallery";
import MenuIndex from "../vue/Pages/Menu/MenuIndex";
import MenuShow from "../vue/Pages/Menu/MenuShow";
import MenuEditMeals from "../vue/Pages/Menu/MenuEditMeals";
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
import MealPublicRecipeNotes from "../vue/Pages/Meals/MealPublicRecipeNotes";
import EditPublicRecipeNotes from "../vue/Pages/Meals/EditPublicRecipeNotes";
import InstagramIndex from "../vue/Pages/Instagram/InstagramIndex";
import CreateDiscountCode from "../vue/Components/Discounts/CreateDiscountCode";
import DiscountsIndex from "../vue/Components/Discounts/DiscountsIndex";
import DiscountEdit from "../vue/Components/Discounts/DiscountEdit";
import MailingListIndex from "../vue/Pages/MailingList/MailingListIndex";
import MenuImages from "../vue/Pages/Menu/MenuImages";
import BatchShoppingList from "../vue/Components/Batches/BatchShoppingList";
import CreateManualOrder from "../vue/Components/Batches/CreateManualOrder";
import PostsList from "../vue/Pages/Blog/List";
import PostEdit from "../vue/Pages/Blog/PostEdit";
import WeeklyBatchReports from "../vue/Pages/Reports/WeeklyBatchReports";
import MealPopularity from "../vue/Pages/Reports/MealPopularity";
import HomePage from "../vue/Pages/HomePage";
import UpcomingKits from "../vue/Pages/Orders/UpcomingKits";
import OrderedKit from "../vue/Pages/Orders/OrderedKit";
import MealNotes from "../vue/Pages/Meals/MealNotes";
import Members from "../vue/Pages/Memberships/Members";
import Member from "../vue/Pages/Memberships/Member";
import Orders from "../vue/Pages/Orders/Orders";
import OrderedKits from "../vue/Pages/Orders/OrderedKits";
import EditOrderedKit from "../vue/Pages/Orders/EditOrderedKit";
import Adjustments from "../vue/Pages/Orders/Adjustments";
import Adjustment from "../vue/Pages/Orders/Adjustment";
import SetFreeRecipes from "../vue/Pages/Menu/SetFreeRecipes";

export default [
    { path: "/", component: HomePage },
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
            { path: "notes", component: MealNotes },
            { path: "public-recipe-notes", component: MealPublicRecipeNotes },
            {
                path: "public-recipe-notes/edit",
                component: EditPublicRecipeNotes,
            },
        ],
    },
    { path: "/menus", component: MenuIndex },
    { path: "/menus/:id", component: MenuShow },
    { path: "/menus/:id/edit-meals", component: MenuEditMeals },
    { path: "/menus/:menu/free-recipes", component: SetFreeRecipes },
    { path: "/menus/:menu/images", component: MenuImages },
    { path: "/orders", component: Orders },
    { path: "/orders/:order", component: Order },
    { path: "/ordered-kits", component: OrderedKits },
    { path: "/orders/upcoming-kits", component: UpcomingKits },
    { path: "/ordered-kits/:kit/show", component: OrderedKit },
    { path: "/ordered-kits/:kit/edit", component: EditOrderedKit },
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

    { path: "/blog/", component: PostsList },
    { path: "/blog/posts/:post/edit", component: PostEdit },
    { path: "/reports/weekly-batch-report", component: WeeklyBatchReports },
    { path: "/reports/meal-popularity", component: MealPopularity },
    { path: "/memberships/members", component: Members },
    { path: "/memberships/members/:member/show", component: Member },
    { path: "/adjustments", component: Adjustments },
    { path: "/adjustments/:adjustment/show", component: Adjustment },
];
