import me from "./me";
import meals from "./meals";
import notifications from "./notifications";
import menus from "./menus";
import orders from "./orders";
import instagram from "./instagram";
import discounts from "./discounts";
import mailinglist from "./mailinglist";
import blog from "./blog";
import { createStore } from "vuex";

const store = createStore({
    modules: {
        me,
        meals,
        notifications,
        menus,
        orders,
        instagram,
        discounts,
        mailinglist,
        blog,
    },
});

export { store };
