import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SalarySPA from './salarySPA';
import { Provider } from 'react-redux';
import store from './redux/store/index';

export default class Salaries extends Component {
    render() {
        return (
            <Provider store={store}>
                <SalarySPA />
            </Provider>
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<Salaries />, document.getElementById('react'));
}