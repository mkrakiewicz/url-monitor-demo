import React from 'react'
import {Button, Modal} from 'react-bootstrap'
import UrlStatsTable from 'components/dashboard/modals/UrlStatsTable'

function UrlStatsModal({urlData, show, onCloseRequest}) {
    return (
        <>
            <Modal show={show} onHide={onCloseRequest} size='lg'>
                <Modal.Header closeButton>
                    <Modal.Title>{urlData.url.url}</Modal.Title>
                </Modal.Header>
                <Modal.Body><UrlStatsTable urlData={urlData}/></Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={onCloseRequest}>
                        Close
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default UrlStatsModal
