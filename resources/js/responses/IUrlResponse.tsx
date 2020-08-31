export default interface IUrlResponse {
    "id": number,
    "user_id": number,
    "url": string,
    "avg_total_loading_time": number|null,
    "avg_redirects_count": number|null,
    "requests_count": number,
    "last_status": number|null,
    "created_at": string,
    "updated_at": string,
}
