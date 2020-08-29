import React, { useCallback, useState } from 'react'
import classNames from 'classnames'

function UrlViewerCard ({ url, viewUrlStatsClicked }) {

    let clickCallback = useCallback(() => {
        viewUrlStatsClicked(url)
    }, [])
    return (<>
            <h3>{url.url}</h3>
            <div className="mb-5 row">
                <div className="col-md-5">
                    <div className="card">
                        <div className="card-header">Actions</div>
                        <div className="card-body">
                            <div className={classNames({ 'row': true })}>
                                <div className="col-sm-6">
                                    <a className="btn btn-primary" onClick={clickCallback}>View Stats</a>
                                </div>
                                <div className="col-sm-6">
                                    <a className='btn btn-light' href={url.url} target={'_blank'}>Visit Site</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-md-4">
                    <div className="card  text-center">
                        <div className="card-header">
                            Average Total Loading Time
                        </div>
                        <div className="card-body">
                            <h4>{getTime(url)}</h4>
                        </div>
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="card  text-center">
                        <div className="card-header">
                            Average Redirects
                        </div>
                        <div className="card-body">
                            <h4>{getRedirects(url)}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

let getTime = function (url) {
    if (url.avg_loading_time === null) {
        if (url.requests_count > 0) {
            return <div className='badge badge-danger'>{url.requests_count}+ Timeouts</div>
        }
        return <div className='badge badge-light'>Not Available</div>
    }
    return parseFloat(url.avg_loading_time.toFixed(3)) + 's'
}

let getRedirects = function (url) {
    if (url.avg_redirect_count === null) {
        if (url.requests_count > 0) {
            return <div className='badge badge-danger'>{url.requests_count}+ Timeouts</div>
        }
        return <div className='badge badge-light'>Not Available</div>
    }
    return parseInt(url.avg_redirect_count)
}

export default UrlViewerCard
