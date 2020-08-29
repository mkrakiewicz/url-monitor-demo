import React, { useCallback, useState } from 'react'
import classNames from 'classnames'

function UrlViewerRow ({ url, viewUrlStatsClicked }) {

    const [isHighlight, setIsHighlight] = useState(false)

    let enableHighlight = useCallback(() => {
        setIsHighlight(true)
    }, [])
    let disableHighlight = useCallback(() => {
        setIsHighlight(false)
    }, [])

    let clickCallback = useCallback(() => {
        viewUrlStatsClicked(url)
    }, [])
    return (
        <div className="card my-5">
            <div className="card-header text-truncate">{url.url}</div>
            <div className="card-body">
                <div className={classNames({ 'row': true, 'py-2': true, 'bg-light': isHighlight })}
                     onMouseEnter={enableHighlight}
                     onMouseLeave={disableHighlight}>
                    <div className="col-sm-7">
                        <div className='badge badge-info'>AVG Loading time: {getTime(url)}</div>
                        <br/>
                        <div className='badge badge-info'>AVG Redirects: {getRedirects(url)}</div>
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
    )
}

let getTime = function (url) {
    if (url.avg_loading_time === null) {
        return 'N/A'
    }
    return parseFloat(url.avg_loading_time) + 's'
}

let getRedirects = function (url) {
    if (url.avg_redirect_count === null) {
        return 'N/A'
    }
    return parseInt(url.avg_redirect_count)
}

export default UrlViewerRow
