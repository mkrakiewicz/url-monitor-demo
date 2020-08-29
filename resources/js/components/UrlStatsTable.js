import React from 'react'
import moment from 'moment'
import UrlStatRow from './UrlStatRow'

function UrlStatsTable ({ urlData }) {
    if (urlData.requests.length === 0) {
        return <>No stats</>
    }

    return (<>
        <div className='row'>
            <div className='col-md-4'>Request time:</div>
            <div className='col-md-4'>Total Loading time:</div>
            <div className='col-md-4'>Redirects:</div>
        </div>
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
    </>)
}

export default UrlStatsTable
