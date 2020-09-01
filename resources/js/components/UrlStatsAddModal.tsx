import {Button, Modal} from 'react-bootstrap'
import UrlStatsAddModalInput from "./UrlAddModalInputRow";
import Logger from "js-logger";

const React = require('react')


export class UrlInput {
    public value;
    private _index;

    constructor(index, value) {
        this._index = index;
        this.value = value;
    }

    get index() {
        return this._index;
    }
}


interface PropTypes {
    inputs: Map<string, UrlInput>,
    show,
    onAdd,
    onChange,
    onSubmit,
    onCloseRequest
}

function UrlStatsAddModal({inputs, show, onAdd, onChange, onSubmit, onCloseRequest}: PropTypes) {
    Logger.debug('inputtts', inputs)
    // let keys = inputs.keys().valueOf()
    return (<>
            <Modal show={show} onHide={onCloseRequest} size='lg'>
                <Modal.Header closeButton>
                    <Modal.Title>Add Urls To Monitor</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <p>You can add several at once.</p>
                    <ul>
                        {Array.from(inputs.keys()).map((key) => {
                            let input = inputs.get(key)
                            return (<li key={input.index}>
                                <UrlStatsAddModalInput value={input.value} index={input.index} onChange={onChange}/>
                            </li>)
                        })}
                    </ul>

                    <button className="btn primary" onClick={onAdd}> +</button>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="primary" onClick={onSubmit}>
                        Add
                    </Button>
                    <Button variant="secondary" onClick={onCloseRequest}>
                        Cancel
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default UrlStatsAddModal
