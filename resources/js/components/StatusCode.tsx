const React = require('react')

function StatusCode ({ statusCode }) {
    let msg = statusCode ? statusCode : 'Not Available'
    return (<div className={`badge ${getBadgeClass(statusCode)}`}>{msg}</div>)
}

let getBadgeClass = statusCode => {
    if (statusCode) {
        switch (statusCode) {
            case 200:
                return 'badge-success'
            default:
                return 'badge-warning'
        }
    }
    return 'badge-light'
}

export default StatusCode
