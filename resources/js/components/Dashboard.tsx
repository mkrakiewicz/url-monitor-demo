import React, {useCallback, useEffect, useReducer, useState} from 'react'
import Url from "entities/Url";
import UrlStatsModal from 'components/dashboard/UrlStatsModal'
import UrlViewerCard from 'components/dashboard/UrlViewerCard'
import IUrlResponse from "responses/IUrlResponse";
import UrlMonitorAddButton from "components/dashboard/UrlMonitorAddButton";
import UrlStatsAddModal, {UrlInput} from "components/dashboard/UrlStatsAddModal";
import Logger from "js-logger";
import {Button} from "react-bootstrap";


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

function Dashboard({user}) {

    const [urls, setUrls] = useState<Array<Url>>([])
    const [isHighlight, setIsHighlight] = useState(false)
    const [showModal, setShowModal] = useState(false)
    const [modalUrlData, setModalUrlData] = useState({requests: [], url: {}})
    const [showAddModal, setShowAddModal] = useState(false)
    const [state, dispatch] = useReducer(reducer, initialState);

    let onSubmit = useCallback((inputs: Array<UrlInput>) => {

        let appurl = (window as any).API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        let items = Array.from(state.inputs.keys()).map((key) => state.inputs.get(key).value)
        window.axios.post(`${appurl}/api/user/${user.id}/bulk-monitor`, {items: items})
            .then((response) => {
                alert('ok')
            }).catch(response => {
            Logger.debug('fail', response)
            if (response.response.status === 401) {
                alert('Unauthorized. Please login again.')
            }
        }).then(()=>{
            refreshUrlList(appurl, user, setUrls)
        })
        setShowAddModal(false)
        // window.location.reload()
    }, [])


    let onChange = useCallback((event, index) => {
        dispatch({type: 'update', index: index, value: event.target.value})
        // inputs[index].value = event.target.value
        // setInputs(inputs)
    }, [])

    let onAdd = () => {
        Logger.debug('clicked')
        // let newInputs = inputs;
        // let index = newInputs.push()
        dispatch({type: 'add'})


        Logger.debug('inputs', state.inputs)
    }

    let onButtonClicked = useCallback(() => {
        setShowAddModal(true)
    }, [])

    let closeAddModal = useCallback(() => {
        setShowAddModal(false)
        dispatch({type: 'reset'})

    }, [])

    useEffect(() => {
        let appurl = (window as any).API_URL
        Logger.debug('appurl', appurl)
        Logger.debug('user', user)
        refreshUrlList(appurl, user, setUrls)
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
            <Button type='primary' onClick={onButtonClicked}>Add</Button>
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
