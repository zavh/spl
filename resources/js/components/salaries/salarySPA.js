import React, { Component } from 'react';
import MainPanel from './mainPanel';
import TaxConfig from './TaxConfig';

export default class SalarySPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            panel:'Main',
            employee_id:'',
        };
        this.switchToTax = this.switchToTax.bind(this);
    }
    
    componentDidMount(){
    }

    switchToTax(employee_id){
        this.setState({
            panel:'TaxConfig',
            employee_id:employee_id,
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
                    <TaxConfig employee_id={this.state.employee_id}/>
                </div>
            );

    }
}