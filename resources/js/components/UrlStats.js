import React, { useState, useEffect } from 'react'
import { Button, Modal } from 'react-bootstrap'

let displayStats = function (urlData) {
    if (urlData.stats.length === 0) {
        return 'No stats'
    }

    return urlData.stats.map((stat) => {
        return (<div className='row' key={stat.time}>
            <div className='col-md-4'>Time: {stat.time}</div>
            <div className='col-md-4'>Loading time: {stat.loadingTime}s</div>
            <div className='col-md-4'>Redirects: {stat.redirectCount}</div>
        </div>)
    })
}

function UrlStats ({ urlData, show, onCloseRequest }) {
    // const [show, setShow] = useState(false);

    // const onCloseRequest = () => setShow(false);
    // const handleShow = () => setShow(true);

    // useEffect(()=>{
    //     setShow(showModal)
    // },[showModal])

    return (
        <>
            <Modal show={show} onHide={onCloseRequest}>
                <Modal.Header closeButton>
                    <Modal.Title>{ urlData.url.url }</Modal.Title>
                </Modal.Header>
                <Modal.Body>{displayStats(urlData)}</Modal.Body>
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

export default UrlStats
