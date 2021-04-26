import { del, get, post } from "./http";

function fetchPosts() {
    return get("/admin/api/blog");
}

function createPost(formData) {
    return post("/admin/api/blog", formData);
}

function updatePost(post_id, formData) {
    return post(`/admin/api/blog/${post_id}`, formData);
}

function deletePost(post_id) {
    return del(`/admin/api/blog/${post_id}`);
}

function publishPost(post_id) {
    return post("/admin/api/published-posts", { post_id });
}

function retractPost(post_id) {
    return del(`/admin/api/published-posts/${post_id}`);
}

export {
    fetchPosts,
    createPost,
    updatePost,
    deletePost,
    publishPost,
    retractPost,
};
