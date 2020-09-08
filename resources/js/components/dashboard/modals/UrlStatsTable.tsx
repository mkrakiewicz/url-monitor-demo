import React from 'react'
import * as Logger from 'js-logger'
import moment from "moment";
import UrlStatRow from 'components/dashboard/modals/UrlStatRow'

function UrlStatsTable({urlData}) {
    if (urlData.requests.length === 0) {
        return <>No stats</>
    }

    return (<>
        <table className='table'>
            <thead>
            <tr>
                <th>Request time</th>
                <th>Total Loading time</th>
                <th>Redirects</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            {urlData.requests.map((request) => {
                let key = `request:${request.id}`
                let timeStr = moment(request.created_at).fromNow()
                let loadTime = <div className='badge badge-danger'>Timed out</div>
                let redirects = <div className='badge badge-danger'>Timed out</div>
                let status;
                if (request.stat) {
                    let stat = request.stat
                    Logger.debug('stat', stat)
                    key = `stat:${stat.id}`
                    loadTime = <>{parseFloat((stat.total_loading_time).toFixed(3)) + 's'}</>
                    redirects = <>{parseInt(stat.redirects_count)}</>
                    status = stat.status
                }

                return (<UrlStatRow key={key}
                                    time={timeStr}
                                    loadTime={loadTime}
                                    redirects={redirects}
                                    statusCode={status}
                />)
            })}
            </tbody>
        </table>
    </>)
}

export default UrlStatsTable
