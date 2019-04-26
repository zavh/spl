import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SStructureSPA from './SStructureSPA';

export default class SalaryStructures extends Component {
    render() {
        return (
            <SStructureSPA />
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<SalaryStructures />, document.getElementById('react'));
}