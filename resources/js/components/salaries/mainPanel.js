import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from '../commons/FileUpload';
import Input from '../commons/Input';
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
    }
    handleMonthChange(month){
        this.setState({month:month});
    }
    handleYearChange(year){
        this.setState({
            fromYear:parseInt(year),
            toYear:parseInt(year) + 1,
            month: 7,
        });
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
                    });
                }
                else if(status === 'fail'){
                    this.setState({message:response.data.message});
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }
    render(){
        if(this.state.status === 'success'){
            return(
                <div>
                    <Input onChange={this.handleYearChange} value={this.state.fromYear} name='year' type='number' labelSize='90px' label='Year' errors={this.state.errors.year}/>
                    <YearNotification fromYear={this.state.fromYear} toYear={this.state.toYear}/>
                    <MonthSelect fromYear={this.state.fromYear} toYear={this.state.toYear} month={this.state.month} onChange={this.handleMonthChange}/>
                    <FileUpload />
                    <table className='table table-sm table-bordered table-striped small'>
                        <tbody>
                            <tr>
                            { Object.keys(this.state.tabheads).map((key, index)=>{
                                return <th key={index}>{this.state.tabheads[key]}</th>
                            })}
                            </tr>
                            {this.state.salaryrow.map((e,i)=>{
                                return <tr key={i}>
                                    {Object.keys(e).map((key,index)=>{
                                        return <td key={index}>
                                            {e[key]}
                                        </td>
                                    })}
                                </tr>
                            })}
                        </tbody>
                    </table>
                </div>
            );
        }
        else if(this.state.status === 'fail'){
            return(
                <div>{this.state.message}</div>
            );
        }
        else return(
            <div>Loading Data</div>
        );
    }
}

function YearNotification(props){
    return(
      <div className='small'>Year {props.fromYear} to {props.toYear} salaries wil be shown</div>
    ) 
}
