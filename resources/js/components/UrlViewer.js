import React, { useState, useEffect, useCallback } from 'react'
import ReactDOM from 'react-dom'
import UrlStats from './UrlStats'

function UrlViewer (user) {

    const [urls, setUrls] = useState([])
    const [showModal, setShowModal] = useState(false)
    const [modalUrlData, setModalUrlData] = useState({ stats: [], url: {} })

    useEffect(() => {
        let appurl = window.API_URL
        console.log('appurl', appurl)
        console.log('user', user)
        axios.get(`${appurl}/api/user/1/urls`).then(response => {
            console.log('success', response)
            setUrls(response.data)
        }).catch(response => {
            console.log('fail', response)
            if (response.response.status === 401) {
                alert("Unauthorized. Please login again.")
            }
        })
    }, [])

    let closeModal = useCallback(() => {
        setShowModal(false)
    }, [])

    let viewStats = useCallback((url) => {
        let appurl = window.API_URL
        axios.get(`${appurl}/api/user/1/bulk-monitor/${url.id}`).then(response => {
            console.log('success', response)
            setModalUrlData(response.data)
            setShowModal(true)
        }).catch(response => {
            console.log('fail', response)
            if (response.response.status === 401) {
                alert("Unauthorized. Please login again.")
            }
        })
    }, [])

    return (
        <div className="card">
            <UrlStats urlData={modalUrlData} show={showModal} onCloseRequest={closeModal}/>
            <div className="card-header">View Urls</div>

            <div className="card-body">
                {urls.map((url) => {
                    // let viewUrlStat = useCallback(() => viewStats(url), [])
                    return (<div key={url.id} className='row'>
                        <div className="col-md-4">{url.url}</div>
                        <div className="col-md-4"><a className="btn btn-primary" onClick={() => viewStats(url)}>View
                            Stats</a></div>
                    </div>)
                })}
            </div>
        </div>
    )
}

export default UrlViewer
