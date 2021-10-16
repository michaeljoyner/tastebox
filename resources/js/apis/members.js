import { del, get, post } from "./http";

const fetchMembers = (page = 1) => {
    return get(`/admin/api/members?page=${page}`);
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

export {
    fetchMembers,
    fetchMember,
    createMemberDiscount,
    updateMemberDiscount,
    deleteMemberDiscount,
};
