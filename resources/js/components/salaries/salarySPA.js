import React, { Component } from 'react';
import MainPanel from './mainPanel';
import TaxConfig from './TaxConfig';
import { Provider } from 'react-redux';
import store from './redux/store/index';

export default class SalarySPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            panel:'Main',
            taxcfg:{},
        };
        this.switchToTax = this.switchToTax.bind(this);
        this.switchToMain = this.switchToMain.bind(this);
    }
    
    componentDidMount(){
    }

    switchToTax(tc){
        this.setState({
            panel:'TaxConfig',
            taxcfg:tc,
        });
    }

    switchToMain(){
        this.setState({
            panel:'Main',
        });
    }

    render() {
        if(this.state.panel === 'Main')
            return (
                <Provider store={store}>
                    <div className="container-fluid">
                        <MainPanel panelChange={this.switchToTax}/>
                    </div>
                </Provider>
            );
        else if(this.state.panel === 'TaxConfig')
            return (
                <Provider store={store}>
                    <div className="container-fluid">
                        <TaxConfig 
                            employee_id={this.state.taxcfg.employee_id}
                            fromYear={this.state.taxcfg.fromYear}
                            toYear={this.state.taxcfg.toYear}
                            panelChange={this.switchToMain}
                            />
                    </div>
                </Provider>
            );

    }
}