'use stritc';
import Url from "../entities/Url";

const React = require('react')
import {useCallback, useEffect, useState} from 'react'
import UrlStatsModal from './UrlStatsModal'
import UrlViewerCard from './UrlViewerCard'
import Logger = require("js-logger");
import IUrlResponse from "../responses/IUrlResponse";


function UrlViewer({user}) {

    const [urls, setUrls] = useState<Array<Url>>([])
    const [isHighlight, setIsHighlight] = useState(false)
    const [showModal, setShowModal] = useState(false)
    const [modalUrlData, setModalUrlData] = useState({requests: [], url: {}})

    useEffect(() => {
        let appurl = (window as any).API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        window.axios.get<Array<IUrlResponse>>(`${appurl}/api/user/${user.id}/urls`).then((response) => {
            let urls = response.data.map((data)=> new Url(data));
            setUrls(urls)
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

    let viewStats = useCallback((url: Url) => {
        let appurl = (window as any).API_URL
        window.axios.get(`${appurl}/api/user/${user.id}/bulk-monitor/${url.id}`).then(response => {
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

        <>
            {urls.length ? urls.map((url) => {
                return (<UrlViewerCard key={url.id} url={url} viewUrlStatsClicked={viewStats}/>)
            }) : <div className='alert alert-info'>
                Nothing here! You can add a url to monitor using the button above.
            </div>}
            <UrlStatsModal urlData={modalUrlData} show={showModal} onCloseRequest={closeModal}/>
        </>
    )
}

export default UrlViewer
