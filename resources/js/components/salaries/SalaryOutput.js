import React, { Component } from 'react';
import { connect } from "react-redux";

import { setMainPanel, setEmployee } from "./redux/actions/index";

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      salaryrows: state.salaryrows,
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
    }

    showTax(e){
        const tc = {
            employee_id:e.target.dataset.index,
        }
        this.props.setEmployee(tc);
        this.props.setMainPanel("TaxConfig");
    }

    render(){
        return(
            <table className='table table-sm table-bordered table-striped small text-right'>
            <tbody className='small'>
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
