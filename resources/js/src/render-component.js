import ReactDOM from 'react-dom'

function renderComponent (appDOM, tagName, component) {
    let elements = appDOM.getElementsByTagName(tagName)
    for (let i in elements) {
        if (elements.hasOwnProperty(i)) {
            let element = elements[i]
            ReactDOM.render(component, element)
        }
    }
}

export default renderComponent
