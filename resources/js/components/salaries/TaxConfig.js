import React, { Component } from 'react';
import axios from 'axios';
import { BrowserRouter as Router, Route, Link } from "react-router-dom";

export default class TaxConfig extends Component{
    componentDidMount(){
        console.log(this.props);
    }
    render(){
        return(<div>I Will Return Yearly Tax Configuration For {this.props.employee_id}</div>);
    }
}