import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SStructureSPA from './SStructureSPA';
import { Provider } from 'react-redux';
import store from './redux/store/index';

export default class SalaryStructures extends Component {
    render() {
        return (
            <Provider store={store}>
                <SStructureSPA />
            </Provider>
            
        );
    }
}

if (document.getElementById('react')) {
    ReactDOM.render(<SalaryStructures />, document.getElementById('react'));
}