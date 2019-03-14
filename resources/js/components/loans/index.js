import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoanSPA from './loanSPA';

export default class Loans extends Component {
    render() {
        return (
            <LoanSPA />
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<Loans />, document.getElementById('react'));
}