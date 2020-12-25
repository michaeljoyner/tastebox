import { get } from "./http";

function fetchMailingList() {
    return get("/admin/api/mailing-list");
}

export { fetchMailingList };
