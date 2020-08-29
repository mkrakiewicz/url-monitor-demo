import React, { useCallback, useEffect, useState } from 'react'
import UrlStatsModal from './UrlStatsModal'
import Logger from 'js-logger'
import UrlViewerCard from './UrlViewerCard'

function UrlViewer ({ user }) {

    const [urls, setUrls] = useState([])
    const [isHighlight, setIsHighlight] = useState(false)
    const [showModal, setShowModal] = useState(false)
    const [modalUrlData, setModalUrlData] = useState({ requests: [], url: {} })

    useEffect(() => {
        let appurl = window.API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        axios.get(`${appurl}/api/user/1/urls`).then(response => {
            setUrls(response.data)
        }).catch(response => {
            Logger.debug('fail', response)
            if (response.response.status === 401) {
                alert('Unauthorized. Please login again.')
            }
        })
    }, [])

    let closeModal = useCallback(() => {
        setShowModal(false)
    }, [])

    let viewStats = useCallback((url) => {
        let appurl = window.API_URL
        axios.get(`${appurl}/api/user/1/bulk-monitor/${url.id}`).then(response => {
            Logger.info('success', response)
            setModalUrlData(response.data)
            setShowModal(true)
        }).catch(response => {
            Logger.error('fail', response)
            if (response.response.status === 401) {
                alert('Unauthorized. Please login again.')
            }
        })
    }, [])

    return (
        <div className="">
            <div className="mb-5"><h1>Your sites</h1></div>
            {urls.map((url) => {
                return (<UrlViewerCard key={url.id} url={url} viewUrlStatsClicked={viewStats}/>)
            })}
            <UrlStatsModal urlData={modalUrlData} show={showModal} onCloseRequest={closeModal}/>
        </div>
    )
}

export default UrlViewer
