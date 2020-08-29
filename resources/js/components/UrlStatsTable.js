import React from 'react'
import moment from 'moment'
import UrlStatRow from './UrlStatRow'

function UrlStatsTable ({ urlData }) {
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
            </tr>
            </thead>
            <tbody>
            {urlData.requests.map((request) => {
                let key = `request:${request.id}`
                let timeStr = moment(request.created_at).fromNow()
                let loadTime = <div className='badge badge-danger'>Timed out</div>
                let redirects = <div className='badge badge-danger'>Timed out</div>
                if (request.stat) {
                    let stat = request.stat
                    key = `stat:${stat.id}`
                    loadTime = parseFloat((stat.total_loading_time).toFixed(3)) + 's'
                    redirects = parseInt(stat.redirects_count)

                }

                return (<UrlStatRow key={key}
                                    time={timeStr}
                                    loadTime={loadTime}
                                    redirects={redirects}
                />)
            })}
            </tbody>
        </table>
    </>)
}

export default UrlStatsTable
