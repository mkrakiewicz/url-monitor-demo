import IUrlResponse from "../responses/IUrlResponse";

class Url {
    private _url: string;
    private _avgLoadingTime: number|null;
    private _avgRedirectsCount: number|null;
    private _lastStatus: number;
    private _id: number;
    private _requestsCount: number;

    constructor(urlData:IUrlResponse) {
        this._id = urlData.id
        this._url = urlData.url
        this._avgLoadingTime = urlData.avg_total_loading_time
        this._avgRedirectsCount = urlData.avg_redirects_count
        this._requestsCount = urlData.requests_count
        this._lastStatus = urlData.last_status
    }

    get url(): string {
        return this._url;
    }

    get avgLoadingTime(): number|null {
        return this._avgLoadingTime;
    }

    get avgRedirectsCount(): number|null {
        return this._avgRedirectsCount;
    }

    get requestsCount(): number {
        return this._requestsCount;
    }

    get lastStatus(): number|null {
        return this._lastStatus;
    }

    get id(): number {
        return this._id;
    }
}

export default Url
