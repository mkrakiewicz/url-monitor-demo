import React, {useCallback, useEffect, useReducer, useState} from 'react'
import Url from "entities/Url";
import UrlStatsModal from 'components/dashboard/modals/UrlStatsModal'
import UrlViewerCard from 'components/dashboard/UrlViewerCard'
import IUrlResponse from "responses/IUrlResponse";
import UrlStatsAddModal, {UrlInput} from "./dashboard/modals/UrlStatsAddModal";
// import Logger from "js-logger";
import {Button} from "react-bootstrap";
import {PuffLoader} from 'react-spinners'
import {useDebounce} from 'use-debounce';

let getEmpty = () => new UrlInput(Math.random(), 'bla')
let getEmptyMap = () => {
    let map = new Map<string, UrlInput>()
    let empty = getEmpty()
    map.set(empty.index, empty)
    return map
}
const initialState = {inputs: getEmptyMap()};

function reducer(state, action) {
    switch (action.type) {
        case 'add':
            let empty = getEmpty()
            state.inputs.set(empty.index, empty)
            return {inputs: state.inputs};
        case 'update':
            let index = action.index
            let item = state.inputs.get(index);
            item.value = action.value
            state.inputs.set(index, item);
            return {inputs: state.inputs};
        case 'reset':
            return {inputs: getEmptyMap()};
        default:
            throw new Error();
    }
}


let refreshUrlList = function (appurl, user, setUrls: (value: (((prevState: Array<Url>) => Array<Url>) | Array<Url>)) => void) {
    return window.axios.get<Array<IUrlResponse>>(`${appurl}/api/user/${user.id}/urls`).then((response) => {
        let urls = response.data.map((data) => new Url(data));
        setUrls(urls)
    }).catch(response => {
        Logger.debug('fail', response)
        if (response.response.status === 401) {
            alert('Unauthorized. Please login again.')
        }
    });
};

let eventSource;

function Dashboard({user, initiallastRequestId}) {

    const [isLoading, setIsLoading] = useState(false)
    const [urls, setUrls] = useState<Array<Url>>([])
    const [isHighlight, setIsHighlight] = useState(false)
    const [lastRequestId, setLastRequestId] = useState(initiallastRequestId)
    const [debouncedLastRequestId] = useDebounce(lastRequestId, 5000)
    const [showModal, setShowModal] = useState(false)
    const [modalUrlData, setModalUrlData] = useState({requests: [], url: {}})
    const [showAddModal, setShowAddModal] = useState(false)
    const [state, dispatch] = useReducer(reducer, initialState);

    let onSubmit = useCallback((inputs: Array<UrlInput>) => {

        let appurl = (window as any).API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        let items = Array.from(state.inputs.keys()).map((key) => state.inputs.get(key).value)
        setIsLoading(true)
        window.axios.post(`${appurl}/api/user/${user.id}/bulk-monitor`, {items: items})
            .then((response) => {
                setIsLoading(false)
            }).catch(response => {
            Logger.debug('fail', response)
            if (response.response.status === 401) {
                alert('Unauthorized. Please login again.')
            }
        }).then(() => {
            setIsLoading(true)
            refreshUrlList(appurl, user, setUrls).then(() => {
                setIsLoading(false)
            })
        })
        setShowAddModal(false)
        // window.location.reload()
    }, [])


    let onChange = useCallback((event, index) => {
        dispatch({type: 'update', index: index, value: event.target.value})
        // inputs[index].value = event.target.value
        // setInputs(inputs)
    }, [])

    let onAdd = useCallback(() => {
        Logger.debug('clicked')
        // let newInputs = inputs;
        // let index = newInputs.push()
        dispatch({type: 'add'})


        Logger.debug('inputs', state.inputs)
    }, [])

    let onButtonClicked = useCallback(() => {
        setShowAddModal(true)
    }, [])

    let closeAddModal = useCallback(() => {
        setShowAddModal(false)
        dispatch({type: 'reset'})

    }, [])

    useEffect(() => {
        eventSource = new EventSource(`/api/user/${user.id}/requests/watch`)
        eventSource.onmessage = (message) => {
            window.Logger.debug('Message', message)
            console.log('Message', message)
            let newLastId = JSON.parse(message.data).lastRequestId.toString()
            // if (lastRequestId !== newLastId) {
            setLastRequestId(newLastId)
            // }
        }
    }, [])

    useEffect(() => {
        let appurl = (window as any).API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        Logger.info('Running refreshUrlList', {'debouncedLastRequestId': debouncedLastRequestId})
        setIsLoading(true)
        refreshUrlList(appurl, user, setUrls).then(() => {
            setIsLoading(false)
        })
    }, [debouncedLastRequestId])


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
            <div className="row mb-2">
                <div className="col-md-8">
                     <PuffLoader loading={isLoading} size={30} css={`display:inline-block`}/>
                </div>
                <div className="col-md-4 text-right">
                    <Button type='primary' onClick={onButtonClicked}>Add Url Monitor</Button>
                </div>
            </div>
            <UrlStatsAddModal onSubmit={onSubmit} show={showAddModal} onCloseRequest={closeModal} inputs={state.inputs}
                              onAdd={onAdd} onChange={onChange}/>

            {urls.length ? urls.map((url) => {
                return (<UrlViewerCard key={url.id} url={url} viewUrlStatsClicked={viewStats}/>)
            }) : <div className='alert alert-info'>
                Nothing here! You can add a url to monitor using the button above.
            </div>}
            <UrlStatsModal urlData={modalUrlData} show={showModal} onCloseRequest={closeModal}/>
        </>
    )
}

export default Dashboard
