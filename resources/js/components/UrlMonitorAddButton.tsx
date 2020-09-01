'use strict';
import {useCallback, useReducer, useState} from 'react'
import UrlStatsAddModal, {UrlInput} from "./UrlStatsAddModal";
import {Button} from "react-bootstrap";
import Logger from "js-logger";
import IUrlResponse from "../responses/IUrlResponse";
import Url from "../entities/Url";

const React = require('react')


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


function UrlMonitorAddButton({user}) {

    const [showModal, setShowModal] = useState(false)
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
        })
        setShowModal(false)
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
        setShowModal(true)
    }, [])

    let closeModal = useCallback(() => {
        setShowModal(false)
        dispatch({type: 'reset'})

    }, [])

    return (

        <>
            <Button type='primary' onClick={onButtonClicked}>Add</Button>
            <UrlStatsAddModal onSubmit={onSubmit} show={showModal} onCloseRequest={closeModal} inputs={state.inputs}
                              onAdd={onAdd} onChange={onChange}/>
        </>
    )
}

export default UrlMonitorAddButton
