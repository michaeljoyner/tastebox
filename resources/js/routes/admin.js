import MealsIndex from "../vue/Pages/Meals/MealsIndex.vue";
import MealGallery from "../vue/Pages/Meals/MealGallery.vue";
import MenuIndex from "../vue/Pages/Menu/MenuIndex.vue";
import MenuShow from "../vue/Pages/Menu/MenuShow.vue";
import MenuEditMeals from "../vue/Pages/Menu/MenuEditMeals.vue";
import Order from "../vue/Pages/Orders/Order.vue";
import CurrentBatch from "../vue/Pages/Batches/CurrentBatch.vue";
import BatchKits from "../vue/Components/Batches/BatchKits.vue";
import BatchMeals from "../vue/Components/Batches/BatchMeals.vue";
import BatchIngredients from "../vue/Components/Batches/BatchIngredients.vue";
import BatchSummary from "../vue/Pages/Batches/BatchSummary.vue";
import MealPageShell from "../vue/Pages/Meals/MealPageShell.vue";
import ShowMealGeneralInfo from "../vue/Components/Meals/ShowMealGeneralInfo.vue";
import EditMealGeneralInfo from "../vue/Components/Meals/EditMealGeneralInfo.vue";
import SetMealIngredients from "../vue/Components/Meals/SetMealIngredients.vue";
import ShowMealNutritionalInfo from "../vue/Components/Meals/ShowMealNutritionalInfo.vue";
import EditNutritionalInfo from "../vue/Components/Meals/EditNutritionalInfo.vue";
import ShowMealInstructions from "../vue/Components/Meals/ShowMealInstructions.vue";
import EditMealInstructions from "../vue/Components/Meals/EditMealInstructions.vue";
import MealActionsPage from "../vue/Pages/Meals/MealActionsPage.vue";
import CreateMeal from "../vue/Components/Meals/CreateMeal.vue";
import ShowMealIngredients from "../vue/Components/Meals/ShowMealIngredients.vue";
import OrganiseIngredients from "../vue/Components/Meals/OrganiseIngredients.vue";
import MealPublicRecipeNotes from "../vue/Pages/Meals/MealPublicRecipeNotes.vue";
import EditPublicRecipeNotes from "../vue/Pages/Meals/EditPublicRecipeNotes.vue";
import InstagramIndex from "../vue/Pages/Instagram/InstagramIndex.vue";
import CreateDiscountCode from "../vue/Components/Discounts/CreateDiscountCode.vue";
import DiscountsIndex from "../vue/Components/Discounts/DiscountsIndex.vue";
import DiscountEdit from "../vue/Components/Discounts/DiscountEdit.vue";
import MailingListIndex from "../vue/Pages/MailingList/MailingListIndex.vue";
import MenuImages from "../vue/Pages/Menu/MenuImages.vue";
import BatchShoppingList from "../vue/Components/Batches/BatchShoppingList.vue";
import CreateManualOrder from "../vue/Components/Batches/CreateManualOrder.vue";
import PostsList from "../vue/Pages/Blog/List.vue";
import PostEdit from "../vue/Pages/Blog/PostEdit.vue";
import WeeklyBatchReports from "../vue/Pages/Reports/WeeklyBatchReports.vue";
import MealPopularity from "../vue/Pages/Reports/MealPopularity.vue";
import HomePage from "../vue/Pages/HomePage.vue";
import UpcomingKits from "../vue/Pages/Orders/UpcomingKits.vue";
import OrderedKit from "../vue/Pages/Orders/OrderedKit.vue";
import MealNotes from "../vue/Pages/Meals/MealNotes.vue";
import Members from "../vue/Pages/Memberships/Members.vue";
import Member from "../vue/Pages/Memberships/Member.vue";
import Orders from "../vue/Pages/Orders/Orders.vue";
import OrderedKits from "../vue/Pages/Orders/OrderedKits.vue";
import EditOrderedKit from "../vue/Pages/Orders/EditOrderedKit.vue";
import Adjustments from "../vue/Pages/Orders/Adjustments.vue";
import Adjustment from "../vue/Pages/Orders/Adjustment.vue";
import SetFreeRecipes from "../vue/Pages/Menu/SetFreeRecipes.vue";
import MealsShoppingList from "../vue/Pages/Meals/CreateMealsShoppingList.vue";
import ShowMealShoppingList from "../vue/Pages/Meals/ShowMealShoppingList.vue";

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
    { path: "/shopping-list", component: MealsShoppingList },
    { path: "/shopping-list/:list", component: ShowMealShoppingList },
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
