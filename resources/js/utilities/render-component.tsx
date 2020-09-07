import ReactDOM from 'react-dom'

function renderComponent(appDOM: HTMLElement, tagName: string, componentCreateCallback) {
    let elements = appDOM.getElementsByTagName(tagName)
    for (let i in elements) {
        if (elements.hasOwnProperty(i)) {
            let element = elements[i]
            let component = componentCreateCallback(element)
            ReactDOM.render(component, element)
        }
    }
}

export default renderComponent
