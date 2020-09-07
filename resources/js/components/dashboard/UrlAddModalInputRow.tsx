import {useCallback} from "react";

import React from 'react';


function UrlStatsAddModalInput({index, value, onChange}) {
    let handleChange = useCallback((event) => {
        onChange(event, index)
    }, [index, value])

    return (
        <input type='text' value={value} onChange={handleChange}/>
    )
}

export default UrlStatsAddModalInput
