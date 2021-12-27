import { get } from "./http";

const fetchActivityLogs = () => {
    return get("/admin/api/activity-logs");
};

export { fetchActivityLogs };
