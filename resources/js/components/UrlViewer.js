import React, { useState, useEffect } from 'react'
import ReactDOM from 'react-dom'

function UrlViewer (user) {

    const [urls, setUrls] = useState([])

    useEffect(() => {
        let appurl = window.API_URL
        console.log('appurl', appurl)
        console.log('user', user)
        axios.get(`${appurl}/api/user/1/urls`).then(response => {
            console.log('success', response)
            setUrls(response.data)
        }).catch(response => {
            console.log('fail', response)
        })
    })

    return (
        <div className="card">
            <div className="card-header">View Urls</div>

            <div className="card-body">
                {urls.map((url) => {
                    return <p>{url.url}</p>
                })}
            </div>
        </div>
    )
}

export default UrlViewer
