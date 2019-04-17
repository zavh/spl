import React, { Component } from 'react';
import { connect } from "react-redux";
import Departments from './Departments';
import { setMainPanel, setEmployee } from "./redux/actions/index";

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setEmployee: employee=> dispatch(setEmployee(employee)),
    };
}

class ConnectedSalaryOutput extends Component {
    constructor(props){
        super(props);
        this.showTax = this.showTax.bind(this);
        this.monthMapping = this.monthMapping.bind(this);
        this.handleDepartmentChange = this.handleDepartmentChange.bind(this);
    }

    showTax(e){
        const tc = {
            employee_id:e.target.dataset.index,
        }
        this.props.setEmployee(tc);
        this.props.setMainPanel("TaxConfig");
    }
    handleDepartmentChange(value){
        this.props.departmentFilter(value);
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
        return(
            <table className='table table-sm table-bordered table-striped small text-right'>
            <tbody className='small'>
                <tr>
                    <td>Salary Year: {this.props.timeline.fromYear} - {this.props.timeline.toYear}</td>
                    <td>Month: {monthtext}</td>
                    <td colSpan={11}></td>
                    <td colSpan={3}>Filter by Department: <Departments onChange={this.handleDepartmentChange}/></td>
                    <td colSpan={3}>Filter by Payout Method</td>
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
