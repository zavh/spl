import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from './UploadMonthData';
import MonthSelect from './monthSelect';
import { connect } from "react-redux";
import { setMainPanel, setEmployee, setPayYear, setSalaryRows } from "./redux/actions/index";

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      salaryrows: state.salaryrows,
      timeline: state.timeline,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setEmployee: employee=> dispatch(setEmployee(employee)),
        setPayYear: timeline=> dispatch(setPayYear(timeline)),
        setSalaryRows: salaryrows=> dispatch(setSalaryRows(salaryrows)),
    };
}
class ConnectedMainPanel extends Component {
    constructor(props){
        super(props);
        this.state = {
            status:'success',
            message:'',
            errors:{
                year:[],
            },
        }
        this.handleMonthChange = this.handleMonthChange.bind(this);
        this.handleYearChange = this.handleYearChange.bind(this);
        this.handleTimelineChange = this.handleTimelineChange.bind(this);
        this.showTax = this.showTax.bind(this);
    }

    handleMonthChange(month){
        let timeline = {
            fromYear:this.props.timeline.fromYear,
            toYear:this.props.timeline.toYear,
            month: parseInt(month),
        };
        this.props.setPayYear(timeline);
    }

    handleYearChange(e){
        let timeline = {
            fromYear:parseInt(e.target.value),
            toYear:parseInt(e.target.value) + 1,
            month: 7,
        };
        this.props.setPayYear(timeline);
    }

    handleTimelineChange(){
        axios.get(`/salaries/dbcheck/${this.props.timeline.fromYear}-${this.props.timeline.month}`)
        .then(
            (response)=>{
                console.log(response);
                status = response.data.status;
                this.setState({status:status})
                if(status === 'success'){
                    this.props.setSalaryRows(response.data.data);
                }
                else if(status === 'fail'){
                    this.setState({
                        message:response.data.message,
                    });
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }
    showTax(e){
        const tc = {
            employee_id:e.target.dataset.index,
        }
        this.props.setEmployee(tc);
        this.props.setMainPanel("TaxConfig");
    }
    componentDidMount(){
        this.setState({
            status:this.props.status,
        });
    }
    render(){
        var Output;
        if(this.state.status === 'success'){
            Output = 
                <div>
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
                </div>
        }
        else if(this.state.status === 'fail'){
            Output = <div>{this.state.message}</div>
        }
        else {
            Output = <div>Loading Data</div>
        }
        
        return(
            <div>
                <div className="form-group row my-1 small">
                    <div className="input-group input-group-sm col-md-8">
                        <div className="input-group-prepend">
                            <span className="input-group-text">Choose Year</span>
                        </div>
                        <input type='number' className="form-control" onChange={this.handleYearChange} value={this.props.timeline.fromYear} />
                        <div className="input-group-append">
                            <span className="input-group-text">Choose Month</span>
                        </div>
                        <MonthSelect fromYear={this.props.timeline.fromYear} toYear={this.props.timeline.toYear} month={this.props.timeline.month} onChange={this.handleMonthChange}/>
                        <div className="input-group-append">
                            <button className="btn btn-outline-success" type="button" onClick={this.handleTimelineChange}>Submit</button>
                        </div>
                    </div>
                    <FileUpload status={this.state.status} timeline={this.props.timeline} onFnishing={this.handleTimelineChange}/>

                </div>
                {Output}
            </div>
        )
    }
}

function YearNotification(props){
    return(
      <div className='small'>Year {props.fromYear} to {props.toYear} salaries wil be shown</div>
    ) 
}

const MainPanel = connect(mapStateToProps, mapDispatchToProps)(ConnectedMainPanel);
export default MainPanel;
