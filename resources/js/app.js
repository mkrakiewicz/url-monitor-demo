/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */
import React from 'react'
import ReactDOM from 'react-dom'
import Example from './components/Example'
import renderComponent from './src/render-component'
import UrlViewer from './components/UrlViewer'

require('./bootstrap')

let appDOM = document.getElementById('app')

renderComponent(appDOM, 'example', <Example/>)
renderComponent(appDOM, 'url-viewer', <UrlViewer/>)
