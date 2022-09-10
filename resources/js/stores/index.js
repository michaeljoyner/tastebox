import me from "./me";
import meals from "./meals";
import notifications from "./notifications";
import menus from "./menus";
import orders from "./orders";
import instagram from "./instagram";
import discounts from "./discounts";
import mailinglist from "./mailinglist";
import blog from "./blog";
import reports from "./reports";
import quotes from "./quotes";
import members from "./members";
import kits from "./kits";
import adjustments from "./adjustments";
import activityLogs from "./activity-logs";
import deliveries from "./deliveries";
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
        reports,
        quotes,
        members,
        kits,
        adjustments,
        activityLogs,
        deliveries,
    },
});

export { store };
