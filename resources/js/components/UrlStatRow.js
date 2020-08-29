import React, { useCallback, useState } from 'react'
import classNames from 'classnames'

function UrlStatRow ({ time, loadTime, redirects }) {
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
        </tr>
    )
}

export default UrlStatRow
