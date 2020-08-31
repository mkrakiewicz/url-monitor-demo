class UrlWithRequestsResponse {
    private _url: string;
    private _avgLoadingTime: Number;
    private _avgRedirectsCount: Number;
    private _lastStatus: Number;

    constructor(urlData) {
        this._url = urlData._url
        this._avgLoadingTime = urlData.avg_total_loading_time
        this._avgRedirectsCount = urlData.avg_redirects_count
        this._lastStatus = urlData.last_status
    }

    get url(): string {
        return this._url;
    }

    get avgLoadingTime(): Number {
        return this._avgLoadingTime;
    }

    get avgRedirectsCount(): Number {
        return this._avgRedirectsCount;
    }

    get lastStatus(): Number {
        return this._lastStatus;
    }
}

export default UrlWithRequestsResponse
