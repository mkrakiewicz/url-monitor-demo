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
        <div className={classNames({ 'row': true, 'bg-light': isHighlight })}
             onMouseEnter={enableHighlightCallback}
             onMouseLeave={disableHighlightCallback}>
            <div className='col-md-4'>{time}</div>
            <div className='col-md-4'>{loadTime}</div>
            <div className='col-md-4'>{redirects}</div>
        </div>
    )
}

export default UrlStatRow
