import { post } from "./http";

function downloadMealImages(meal_ids) {
    return post("/admin/api/fetch-images", { meal_ids });
}

export { downloadMealImages };
