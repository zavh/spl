import React, { Component } from 'react';

export default class MonthSelect extends Component {
    constructor(props){
        super(props);
        this.handleMonthChange = this.handleMonthChange.bind(this);
    }

    handleMonthChange(e){
        this.props.onChange(e.target.value);
    }
    render(){
        return(
        <select value={this.props.month} className='form-control' onChange={this.handleMonthChange}>
            <option value='7'>{this.props.fromYear}-July</option>
            <option value='8'>{this.props.fromYear}-August</option>
            <option value='9'>{this.props.fromYear}-September</option>
            <option value='10'>{this.props.fromYear}-October</option>
            <option value='11'>{this.props.fromYear}-November</option>
            <option value='12'>{this.props.fromYear}-December</option>
            <option value='1'>{this.props.toYear}-January</option>
            <option value='2'>{this.props.toYear}-February</option>
            <option value='3'>{this.props.toYear}-March</option>
            <option value='4'>{this.props.toYear}-April</option>
            <option value='5'>{this.props.toYear}-May</option>
            <option value='6'>{this.props.toYear}-June</option>
        </select>
        ) 
    }
}