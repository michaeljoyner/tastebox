import { get } from "./http";

const fetchDeliveryAreas = () => get("/admin/api/delivery-areas");

export { fetchDeliveryAreas };
