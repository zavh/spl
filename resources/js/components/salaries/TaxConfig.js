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
        axios.get(`/salaries/taxconfig/yearly_income_${this.props.fromYear}_${this.props.toYear}/${this.props.employee_id}`)
        .then(
            (response)=>{
                console.log(response);
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
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