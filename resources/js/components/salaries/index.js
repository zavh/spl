import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SalarySPA from './salarySPA';

export default class Salaries extends Component {
    render() {
        return (
            <SalarySPA />
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<Salaries />, document.getElementById('react'));
}