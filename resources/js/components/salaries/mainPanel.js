import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from './UploadMonthData';
import MonthSelect from './monthSelect';

export default class MainPanel extends Component {
    constructor(props){
        super(props);
        this.state = {
            tabheads:{},
            salaryrow:[],
            status:'',
            message:'',
            fromYear:'',
            toYear:'',
            month:'',
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
        this.setState({month:month});
    }
    handleYearChange(e){
        this.setState({
            fromYear:parseInt(e.target.value),
            toYear:parseInt(e.target.value) + 1,
            month: 7,
        });
    }
    handleTimelineChange(){
        axios.get(`/salaries/dbcheck/${this.state.fromYear}-${this.state.month}`)
        .then(
            (response)=>{
                console.log(response);
                status = response.data.status;
                this.setState({status:status})
                if(status === 'success'){
                    this.setState({
                        tabheads : response.data.tabheads,
                        salaryrow : response.data.data,
                        fromYear : response.data.fromYear,
                        toYear : response.data.toYear,
                        month : response.data.month,
                        status:status,
                    });
                }
                else if(status === 'fail'){
                    this.setState({
                        message:response.data.message,
                        status:status,
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
            fromYear:this.state.fromYear,
            toYear:this.state.toYear,
        }
        this.props.panelChange(tc);
    }
    componentDidMount(){
        axios.get('/salaries/dbcheck')
        .then(
            (response)=>{
                console.log(response);
                status = response.data.status;
                this.setState({status:status})
                if(status === 'success'){
                    this.setState({
                        tabheads : response.data.tabheads,
                        salaryrow : response.data.data,
                        fromYear : response.data.fromYear,
                        toYear : response.data.toYear,
                        month : response.data.month,
                        status:status,
                    });
                }
                else if(status === 'fail'){
                    this.setState({
                        message:response.data.message,
                        status:status,
                    });
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }
    render(){
        var Output;
        if(this.state.status === 'success'){
            Output = 
                <div>
                    <table className='table table-sm table-bordered table-striped small text-right'>
                        <tbody className='small'>
                            <tr>
                            { Object.keys(this.state.tabheads).map((key, index)=>{
                                return <th key={index}>{this.state.tabheads[key]}</th>
                            })}
                            </tr>
                            {this.state.salaryrow.map((e,i)=>{
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
                        <input type='number' className="form-control" onChange={this.handleYearChange} value={this.state.fromYear} />
                        <div className="input-group-append">
                            <span className="input-group-text">Choose Month</span>
                        </div>
                        <MonthSelect fromYear={this.state.fromYear} toYear={this.state.toYear} month={this.state.month} onChange={this.handleMonthChange}/>
                        <div className="input-group-append">
                            <button className="btn btn-outline-success" type="button" onClick={this.handleTimelineChange}>Submit</button>
                        </div>
                    </div>
                    <FileUpload status={this.state.status} fromYear={this.state.fromYear} toYear={this.state.toYear} month={this.state.month}/>

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


