import React, { Component } from 'react';
import { connect } from "react-redux";
import Departments from './Departments';
import PayOutMode from './PayOutMode';
import { setMainPanel, setEmployee, setFilters } from "./redux/actions/index";

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      filters: state.filters,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setEmployee: employee=> dispatch(setEmployee(employee)),
        setFilters: filters=> dispatch(setFilters(filters)),
    };
}

class ConnectedSalaryOutput extends Component {
    constructor(props){
        super(props);
        this.showTax = this.showTax.bind(this);
        this.monthMapping = this.monthMapping.bind(this);
        this.handleDepartmentChange = this.handleDepartmentChange.bind(this);
        this.handlePOMChange = this.handlePOMChange.bind(this);
    }

    showTax(e){
        const tc = {
            employee_id:e.target.dataset.index,
        }
        this.props.setEmployee(tc);
        this.props.setMainPanel("TaxConfig");
    }
    handleDepartmentChange(value){
        let filters = {
            department: value,
            pay_out_mode: this.props.filters.pay_out_mode
        };
        this.props.setFilters(filters);
        this.props.handleFilterChange(filters);
    }
    handlePOMChange(value){
        let filters = {
            department: this.props.filters.department,
            pay_out_mode: value
        };
        this.props.setFilters(filters);
        this.props.handleFilterChange(filters);
    }
    monthMapping(month){
        let months=[];
        months[7] = this.props.timeline.fromYear+'- July';
        months[8] = this.props.timeline.fromYear+'- August';
        months[9] = this.props.timeline.fromYear+'- September';
        months[10] = this.props.timeline.fromYear+'- October';
        months[11] = this.props.timeline.fromYear+'- November';
        months[12] = this.props.timeline.fromYear+'- December';
        months[1] = this.props.timeline.toYear+'- January';
        months[2] = this.props.timeline.toYear+'- February';
        months[3] = this.props.timeline.toYear+'- March';
        months[4] = this.props.timeline.toYear+'- April';
        months[5] = this.props.timeline.toYear+'- May';
        months[6] = this.props.timeline.toYear+'- June';
        return months[month];
    }
    render(){
        let monthtext = this.monthMapping(this.props.timeline.month);
        if(this.props.filters.pay_out_mode == 'bank')
            var download = <a href='javascript:void(0)' className='btn btn-sm btn-outline-secondary badge badge-pill'>Download</a>
        else var download = null
        return(
            <table className='table table-sm table-bordered table-striped small text-right'>
            <tbody className='small'>
                <tr className='text-center'>
                    <td colSpan={3}>Salary Year: {this.props.timeline.fromYear} - {this.props.timeline.toYear}</td>
                    <td colSpan={3}>Month: {monthtext}</td>
                    <td colSpan={6}>Filter by Department : <Departments onChange={this.handleDepartmentChange} selected={this.props.filters.department}/></td>
                    <td colSpan={6}>Filter by Payout Method : <PayOutMode onChange={this.handlePOMChange} selected={this.props.filters.pay_out_mode}/></td>
                    <td colSpan={1}>{download}</td>
                </tr>
                <tr>
                { Object.keys(this.props.tabheads).map((key, index)=>{
                    return <th key={index}>{this.props.tabheads[key]}</th>
                })}
                </tr>
                {this.props.salaryrows.map((e,i)=>{
                    return <tr key={i}>
                        {Object.keys(e).map((key,index)=>{
                            if(key=='monthly_tax')
                                return <td key={index}>
                                    <a href='javascript:void(0)' onClick={this.showTax} data-index={e.employee_id}>{e[key]}</a>
                                </td>
                            else return <td key={index}>
                                    {e[key]}
                                </td>
                        })}
                    </tr>
                })}
            </tbody>
        </table>
        );
    }
}

const SalaryOutput = connect(mapStateToProps, mapDispatchToProps)(ConnectedSalaryOutput);
export default SalaryOutput;
