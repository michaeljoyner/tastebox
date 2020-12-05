import { get } from "./http";

function fetchInstagramFeed() {
    return get("/admin/api/instagram-feed");
}

export { fetchInstagramFeed };
