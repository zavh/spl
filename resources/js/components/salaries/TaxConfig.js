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
            taxable:{},
            taxable_salary:0,
            slabinfo:[],
            taxbeforeinv:0,
            MaxInvestment:0,
            TIRebate:0,
            finalTax:0
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
                    taxable:response.data.taxable,
                    taxable_salary:response.data.taxable_salary,
                    slabinfo:response.data.slabinfo,
                    taxbeforeinv:response.data.taxbeforeinv,
                    MaxInvestment:response.data.MaxInvestment,
                    TIRebate:response.data.TIRebate,
                    finalTax:response.data.finalTax,
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
        <div className='container-fluid'>
            <div className='row'>
                <div className="col-md-6">
                    <SalaryTable monthdata={this.state.monthdata} totaldata={this.state.totaldata}/>
                </div>
                <div className="col-md-6">
                    <TaxableTable taxable={this.state.taxable} tabheads={this.props.tabheads} taxable_salary={this.state.taxable_salary}/>
                    <SlabTable 
                        slabinfo={this.state.slabinfo} 
                        taxbeforeinv={this.state.taxbeforeinv} 
                        taxable_salary={this.state.taxable_salary}
                        MaxInvestment={this.state.MaxInvestment}
                        TIRebate={this.state.TIRebate}
                        finalTax={this.state.finalTax}
                        />
                </div>
            </div>
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

function TaxableTable(props){
    return(
        <table className='table table-sm table-bordered table-striped small text-right table-dark'>
            <tbody className="small">
                <tr><th>Heads of Income</th><th>Actual</th><th>Exempted</th><th>Taxable Income</th></tr>
                {Object.keys(props.taxable).map((key, index)=>{
                    return(
                        <tr key={index}>
                            <td>{props.tabheads[key]}</td>
                            <td>{props.taxable[key]['actual']}</td>
                            <td>{props.taxable[key]['exempted']}</td>
                            <td>{props.taxable[key]['taxable']}</td>
                        </tr>
                    )
                })}
                <tr><th colSpan={3}>Total Taxable Income</th><td>{props.taxable_salary}</td></tr>
            </tbody>
        </table>
    );
}

function SlabTable(props){
    return(
        <table className='table table-sm table-bordered table-striped small text-right table-dark text-center'>
            <tbody className="small">
                <tr><th colSpan={5}>Tax Calculatioin</th></tr>
                <tr><th>Slab Tier</th><th>Slab Amount</th><th>Slab Percentage</th><th>Taxable Amount</th><th>Tax</th></tr>
                {props.slabinfo.map((slab, index)=>{
                    return(
                    <tr key={index}>
                        {slab.map((info, c)=>{
                            return(
                                <td key={c}>{info}</td>
                            )
                        })}
                    </tr>
                    )
                })}
                <tr><th colSpan={3}>Total</th><td>{props.taxable_salary}</td><td>{props.taxbeforeinv}</td></tr>
                <tr><th colSpan={3}>Maximum Investment Required ( 25% of {props.taxable_salary} )</th><td>{props.MaxInvestment}</td><td></td></tr>
                <tr><th colSpan={3}>Tax Rebate on Investment ( 15% of {props.MaxInvestment})</th><td>( - )</td><td>{props.TIRebate}</td></tr>
                <tr><th colSpan={3}>Final Tax</th><td></td><td>{props.finalTax}</td></tr>
            </tbody>
        </table>
    );
}
const TaxConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedTaxConfig);
export default TaxConfig;