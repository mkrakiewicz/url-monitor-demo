import React, { useState, useEffect } from 'react'
import { Button, Modal } from 'react-bootstrap'
import moment from 'moment'

function UrlStats ({ urlData, show, onCloseRequest }) {
    return (
        <>
            <Modal show={show} onHide={onCloseRequest}>
                <Modal.Header closeButton>
                    <Modal.Title>{urlData.url.url}</Modal.Title>
                </Modal.Header>
                <Modal.Body>{getStatsDivs(urlData)}</Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={onCloseRequest}>
                        Close
                    </Button>
                    <Button variant="primary" onClick={onCloseRequest}>
                        Save Changes
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

let getStatDiv = function (key, requestTimeStr, time, redirects) {
    return <div className='row' key={key}>
        <div className='col-md-4'>Request time: {requestTimeStr}</div>
        <div className='col-md-4'>Loading time: {time}</div>
        <div className='col-md-4'>Redirects: {redirects}</div>
    </div>
}
let getStatsDivs = function (urlData) {
    if (urlData.requests.length === 0) {
        return 'No stats'
    }

    return urlData.requests.map((request) => {
        if (request.stat === null) {
            return (getStatDiv(`request:${request.id}`, moment(request.created_at).fromNow(), 'Timed out', 'Timed out'))
        }
        let stat = request.stat
        let timeStr = moment(stat.created_at).fromNow()
        return (getStatDiv(`stat:${stat.id}`, timeStr, stat.total_loading_time, stat.redirects_count))
    })
}

export default UrlStats
