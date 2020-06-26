import { del } from "./http";

function deleteMeal(meal_id) {
    return del(`/admin/api/meals/${meal_id}`);
}

export { deleteMeal };
