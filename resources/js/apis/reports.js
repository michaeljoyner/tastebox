import { get } from "./http";

function fetchRecentWeeklyBatchReports() {
    return get("/admin/api/reports/weekly-batches");
}

export { fetchRecentWeeklyBatchReports };
