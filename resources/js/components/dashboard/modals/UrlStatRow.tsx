import classNames from 'classnames'
import StatusCode from 'components/dashboard/StatusCode'
import {useCallback, useState} from "react";
import React from 'react';

function UrlStatRow ({ time, loadTime, redirects, statusCode }) {
    const [isHighlight, setIsHighlight] = useState(false)

    let enableHighlightCallback = useCallback(() => {
        setIsHighlight(true)
    }, [])
    let disableHighlightCallback = useCallback(() => {
        setIsHighlight(false)
    }, [])

    return (
        <tr className={classNames({ 'bg-light': isHighlight })}
            onMouseEnter={enableHighlightCallback}
            onMouseLeave={disableHighlightCallback}>
            <td>{time}</td>
            <td>{loadTime}</td>
            <td>{redirects}</td>
            <td><StatusCode statusCode={statusCode}/></td>
        </tr>
    )
}

export default UrlStatRow
