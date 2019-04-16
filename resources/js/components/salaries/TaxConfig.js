import React, { Component } from 'react';
import axios from 'axios';
import { connect } from "react-redux";
import { setMainPanel} from "./redux/actions/index";

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      timeline: state.timeline,
      targetEmployee: state.targetEmployee,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
    };
}


class ConnectedTaxConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            monthdata:[],
            totaldata:{},
        }
        this.backToMain = this.backToMain.bind(this);
    }
    backToMain(){
        this.props.setMainPanel("Main");
    }
    componentDidMount(){
        axios.get(`/salaries/taxconfig/yearly_income_${this.props.timeline.fromYear}_${this.props.timeline.toYear}/${this.props.targetEmployee.employee_id}`)
            .then((response)=>{
                this.setState({
                    monthdata:response.data.monthdata,
                    totaldata:response.data.totaldata,
                })
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
            <SalaryTable monthdata={this.state.monthdata} totaldata={this.state.totaldata}/>
            <a href='javascript:void(0)' onClick={this.backToMain}>Back</a>
        </div>
        );
    }
}

function SalaryTable(props){
    return(
        <table className='table table-sm table-bordered table-striped small text-right table-dark'>
            <tbody className='small'>
                <tr className='bg-primary'><th>Month</th><th>Basic</th><th>House Rent</th><th>Conveyance</th><th>Medical Allowance</th><th>PF Company</th><th>Bonus</th><th>Extra</th><th>Less</th><th>Tax</th></tr>
                {props.monthdata.map((md,index)=>{
                    return(
                        <tr key={index}>
                            {Object.keys(md).map((key,count)=>{
                                return(
                                    <td key={count}>
                                        {md[key]}
                                    </td>
                                )
                            })}
                        </tr>
                    );
                })}
                <tr className='bg-info text-white'>
                    <th>Total : </th>
                    {Object.keys(props.totaldata).map((td,i)=>{
                        return <th key={i}>{props.totaldata[td]}</th>
                    })}
                </tr>
            </tbody>
        </table>
    );
}

const TaxConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedTaxConfig);
export default TaxConfig;

