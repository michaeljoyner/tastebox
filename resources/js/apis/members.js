import { del, get, post } from "./http";

const fetchMembers = (page = 1, term = "") => {
    const query = new URLSearchParams();
    query.set("page", page);
    if (term) {
        query.set("q", term);
    }
    return get(`/admin/api/members?${query.toString()}`);
};

const fetchMember = (member_id) => {
    return get(`/admin/api/members/${member_id}`);
};

const createMemberDiscount = (member_id, formData) => {
    return post(`/admin/api/members/${member_id}/discounts`, formData);
};

const updateMemberDiscount = (discount_id, formData) => {
    return post(`/admin/api/member-discounts/${discount_id}`, formData);
};

const deleteMemberDiscount = (discount_id) => {
    return del(`/admin/api/member-discounts/${discount_id}`);
};

const createGeneralMemberDiscounts = (formData) => {
    return post("/admin/api/general-member-discounts", formData);
};

const updateGeneralMemberDiscounts = (tag, formData) => {
    return post(`/admin/api/general-member-discounts/${tag}`, formData);
};

const deleteGeneralMemberDiscounts = (tag) => {
    return del(`/admin/api/general-member-discounts/${tag}`);
};

export {
    fetchMembers,
    fetchMember,
    createMemberDiscount,
    updateMemberDiscount,
    deleteMemberDiscount,
    createGeneralMemberDiscounts,
    updateGeneralMemberDiscounts,
    deleteGeneralMemberDiscounts,
};
