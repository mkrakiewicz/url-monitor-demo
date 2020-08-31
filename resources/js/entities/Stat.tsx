class Stat {
    private _id: number;
    private _totalLoadingTime: number;
    private _redirectsCount: number;
    private _status: number;

    constructor(statData) {
        this._id = statData.id
        this._status = statData.status
        this._totalLoadingTime = statData.total_loading_time
        this._redirectsCount = statData.redirects_count
    }

    get id(): number {
        return this._id;
    }

    get totalLoadingTime(): number {
        return this._totalLoadingTime;
    }

    get redirectsCount(): number {
        return this._redirectsCount;
    }

    get status(): number {
        return this._status;
    }
}

export default Stat
