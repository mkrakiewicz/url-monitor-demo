import Url from "../entities/Url";
import {useCallback} from 'react'
import classNames from 'classnames'
import StatusCode from './StatusCode'
import Logger from "js-logger";

const React = require('react')

type PropTypes = {
    url: Url,
    viewUrlStatsClicked: any
}
const UrlViewerCard = ({url, viewUrlStatsClicked}: PropTypes) => {

    let clickCallback = useCallback(() => {
        viewUrlStatsClicked(url)
    }, [])
    return (<>
            <h3>{url.url}</h3>
            <div className="mb-5 row">
                <div className="col-sm-4">
                    <div className="card">
                        <div className="card-header">Actions</div>
                        <div className="card-body">
                            <div className={classNames({'row': true})}>
                                <div className="col-sm-6">
                                    <a className="btn btn-primary" onClick={clickCallback}>
                                        <i className="fas fa-eye"></i>&nbsp; Stats
                                    </a>
                                </div>
                                <div className="col-sm-6">
                                    <a className='btn btn-light' href={url.url} target={'_blank'}>Visit Site</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-sm-3">
                    <div className="card  text-center">
                        <div className="card-header">
                            Average TLL
                        </div>
                        <div className="card-body">
                            <h4>{getTime(url)}</h4>
                        </div>
                    </div>
                </div>
                <div className="col-sm">
                    <div className="card  text-center">
                        <div className="card-header">
                            Average Redirects
                        </div>
                        <div className="card-body">
                            <h4>{getRedirects(url)}</h4>
                        </div>
                    </div>
                </div>
                <div className="col-sm">
                    <div className="card  text-center">
                        <div className="card-header">
                            Last Status
                        </div>
                        <div className="card-body">
                            <h4><StatusCode statusCode={url.lastStatus}/></h4>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

let getTime = function (url:Url) {
    if (url.avgLoadingTime === null) {
        if (url.requestsCount > 0) {
            return <div className='badge badge-danger'>{url.requestsCount}+ Timeouts</div>
        }
        return <div className='badge badge-light'>Not Available</div>
    }
    Logger.debug('urll',url)
    return parseFloat(url.avgLoadingTime.toFixed(3)) + 's'
}

let getRedirects = function (url:Url) {
    if (url.avgRedirectsCount === null) {
        if (url.requestsCount > 0) {
            return <div className='badge badge-danger'>{url.requestsCount}+ Timeouts</div>
        }
        return <div className='badge badge-light'>Not Available</div>
    }
    return parseInt(url.avgRedirectsCount.toString())
}

export default UrlViewerCard
