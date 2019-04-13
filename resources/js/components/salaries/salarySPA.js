import React, { Component } from 'react';
import MainPanel from './mainPanel';
import TaxConfig from './TaxConfig';

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
                    <div className="container-fluid">
                        <MainPanel panelChange={this.switchToTax}/>
                    </div>
            );
        else if(this.state.panel === 'TaxConfig')
            return (
                <div className="container-fluid">
                    <TaxConfig 
                        employee_id={this.state.taxcfg.employee_id}
                        fromYear={this.state.taxcfg.fromYear}
                        toYear={this.state.taxcfg.toYear}
                        panelChange={this.switchToMain}
                        />
                </div>
            );

    }
}