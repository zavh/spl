import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoanSPA from './loanSPA';
import { Provider } from 'react-redux';
import store from './redux/store/index';

export default class Loans extends Component {
    render() {
        return (
            <Provider store={store}>
                <LoanSPA />
            </Provider>
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<Loans />, document.getElementById('react'));
}