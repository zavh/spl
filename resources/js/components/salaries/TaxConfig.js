import React, { Component } from 'react';
import axios from 'axios';

export default class TaxConfig extends Component{
    constructor(props){
        super(props);

        this.backToMain = this.backToMain.bind(this);
    }
    backToMain(){
        this.props.panelChange();
    }
    componentDidMount(){

    }
    render(){
        return(
        <div>
            I Will Return Yearly Tax Configuration For {this.props.employee_id} for the financial year {this.props.fromYear} to {this.props.toYear}
            <a href='javascript:void(0)' onClick={this.backToMain}>Back</a>
        </div>
        );
    }
}