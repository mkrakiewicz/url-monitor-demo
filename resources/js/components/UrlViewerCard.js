import React, { useCallback, useState } from 'react'
import classNames from 'classnames'

function UrlViewerCard ({ url, viewUrlStatsClicked }) {

    let clickCallback = useCallback(() => {
        viewUrlStatsClicked(url)
    }, [])
    return (<>
            <h3>{url.url}</h3>
            <div className="card mb-5">
                <div className="card-body">
                    <div className={classNames({ 'row': true, 'py-2': true })}>
                        <div className="col-sm-7">
                            Average Total Loading Time<div className='badge badge-info'>{getTime(url)}</div>
                            <br/>
                            Average Redirects<div className='badge badge-info'>{getRedirects(url)}</div>
                        </div>
                        <div className="col-sm-3 text-right">
                            <a className="btn btn-primary" onClick={clickCallback}>View Stats</a>
                        </div>
                        <div className="col-sm-2 text-right">
                            <a className='btn btn-primary' href={url.url} target={'_blank'}>Visit</a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

let getTime = function (url) {
    if (url.avg_loading_time === null) {
        return 'N/A'
    }
    return parseFloat(url.avg_loading_time.toFixed(3)) + 's'
}

let getRedirects = function (url) {
    if (url.avg_redirect_count === null) {
        return 'N/A'
    }
    return parseInt(url.avg_redirect_count)
}

export default UrlViewerCard
