/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */
import renderComponent from './src/render-component'
import UrlViewer from './components/UrlViewer'
const React = require("react");
import {AxiosInstance} from "axios";
import UrlMonitorAddButton from "./components/UrlMonitorAddButton";

declare global {
    interface Window {
        _: any;
        $: any;
        jQuery: any;
        Popper: any;
        API_URL: any;
        APP_ENV: any;
        LOGS_ENABLED: any;
        axios: AxiosInstance;
    }
}

require('./bootstrap')

let appDOM = document.getElementById('app')

renderComponent(appDOM, 'url-viewer', (element) => {
    let user = JSON.parse(element.dataset.user)
    return <UrlViewer user={user}/>
})


renderComponent(appDOM, 'url-add-button', (element) => {
    let user = JSON.parse(element.dataset.user)
    return <UrlMonitorAddButton user={user}/>
})
