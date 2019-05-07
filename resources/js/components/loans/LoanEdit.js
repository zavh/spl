import React, { Component } from 'react';
import Input from '../commons/Input';
import Submit from '../commons/submit';
import LoanType from './loantype';
import Readonly from '../commons/Readonly';
import axios from 'axios';
import Card from '../commons/Card';
import { connect } from "react-redux";
import { modActiveLoan, setSchedule, setActiveLoans } from "./redux/actions/index";
import {C101} from "./codes/index";
function mapStateToProps (state)
{
  return {
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        modActiveLoan: loans=> dispatch(modActiveLoan(loans)),
        setSchedule: loans=> dispatch(setSchedule(loans)),
        setActiveLoans: loans=> dispatch(setActiveLoans(loans)),
     };
}
class ConnectedLoanEdit extends Component {
    constructor(props){
        super(props);
        this.state = {
            name:'',
            employee_id:'',
            department:'',
            loan_name:'',
            amount:'',
            start_date:'',
            tenure:'',
            interest:'',
            loan_type:'0',
            errors:{
                loan_name:[],
                amount:[],
                start_date:[],
                tenure:[],
                interest:[],
                loan_type:[],
            },
            editErrorState:false,
            loan_status:{},
        };
        this.handleLtpeChange  = this.handleLtpeChange.bind(this);
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
        this.handleElementChange  = this.handleElementChange.bind(this);
        this.handleDelete  = this.handleDelete.bind(this);
    }

    getLoan(){
        axios.get('/loans/'+this.props.match.params.id+'/edit')
            .then((response)=>{
                console.log(response);
                let index = this.props.match.params.index;
                this.setState({
                    name:this.props.activeloans[index].name,
                    employee_id:this.props.activeloans[index].employee_id,
                    department:response.data.department,
                    loan_name:response.data.data.loan_name,
                    amount:response.data.data.amount,
                    start_date:response.data.data.start_date,
                    tenure:response.data.data.tenure,
                    interest:response.data.data.interest,
                    loan_type:response.data.data.loan_type,
                    loan_status:response.data.loan_status,
                })
                this.props.setSchedule( JSON.parse(response.data.data.schedule));
            }
        );
    }
    
    componentDidMount(){
        this.getLoan();
    }
    componentWillUnmount(){
        this.props.setSchedule({});
    }
    handleElementChange(name, value){
        this.setState({[name]:value});
    }

    handleLtpeChange(value){
        this.setState({loan_type:value})
    }

    handleDelete(){
        var c = confirm(C101);
        if(c){
            axios.delete(`/loans/${this.props.match.params.id}`)
            .then((response)=>{
                if(response.data.status ==  'success'){
                    let loans = [...this.props.activeloans];
                    let index = parseInt(this.props.match.params.index);
                    let firstslice = loans.slice(0,index);
                    let secondslice = loans.slice(index + 1);
                    let result = firstslice.concat(secondslice);
                    this.props.setActiveLoans(result);
                    this.props.history.push('/loans');
                }
            })
            .catch(function (error) {
                console.log(error);
              });
        }
    }

    handleSubmit(e){
        e.preventDefault();
        if(this.state.editErrorState)
            this.clearErrorBag();
        let status = 'success';
        axios.put(`/loans/${this.props.match.params.id}`, {
            loan_name:this.state.loan_name,
            amount:this.state.amount,
            start_date:this.state.start_date,
            tenure:this.state.tenure,
            interest:this.state.interest,
            loan_type:this.state.loan_type,
          })
          .then((response)=>{
            console.log(response);
            status = response.data.status;
            if(status == 'failed'){
                this.setState({editErrorState:true});
                let e = response.data.errors;
                let errors = Object.assign({}, this.state.errors);
                for(var key in errors){
                    errors[key] =  key in e ? e[key]:[]
                }
                this.setState({errors});
            }
            else if(status == 'success'){
                this.props.modActiveLoan(
                {
                    index:this.props.match.params.index,
                    loan:response.data,
                });
            }
          })
          .catch(function (error) {
            console.log(error);
          });
    }

    handleCancel(e){
        e.preventDefault();
        this.props.history.push('/loans');
    }

    clearErrorBag(){
        this.setState({editErrorState:false});
        let errors = Object.assign({}, this.state.errors);
        for(var key in errors){
            errors[key] = [];
        }
        this.setState({errors});
    }

    render() {
        return (
            <div className="row justify-content-between">
                <div className="col-md-6">
                    <Card title='Loan Status'>
                        <table className='table'>
                            <tbody>
                                {Object.keys(this.state.loan_status).map((key,index)=>{
                                    return(
                                        <tr key={index}>
                                            <td className='p-0'><span className='mx-2'>{key}</span></td><td className='p-0'>{this.state.loan_status[key]}</td>
                                        </tr>
                                    )
                                })}
                            </tbody>
                        </table>
                        <div className='row m-4'>
                            <div className="col-4 m-0 pr-1">
                                <button type="button" className="btn btn-outline-primary btn-sm btn-block">Deactivate</button>
                            </div>
                            <div className="col-4 m-0 pr-1">
                                <button type="button" className="btn btn-outline-primary btn-sm btn-block" onClick={this.handleDelete}>Delete</button>
                            </div>
                            <div className="col-4 m-0 pr-1">
                                <button type="button" className="btn btn-outline-primary btn-sm btn-block" onClick={this.handleCancel}>Go Back</button>
                            </div>
                        </div>
                    </Card>
                </div>
                <div className="col-md-6">
                    <Card title='Edit Loan'>
                        <div className='m-2'>
                            <Readonly labelSize='120px' label='Department' value={this.state.department}/>
                            <Readonly labelSize='120px' label='Employee ID' value={this.state.employee_id}/>
                            <Readonly labelSize='120px' label='Employee Name' value={this.state.name}/>
                            <form onSubmit={this.handleSubmit}>
                                <Input onChange={this.handleElementChange} value={this.state.loan_name}  name='loan_name'  type='text'   labelSize='120px' label='Loan title'  errors={this.state.errors.loan_name}/>
                                <Input onChange={this.handleElementChange} value={this.state.amount}     name='amount'     type='number' labelSize='120px' label='Amount'      errors={this.state.errors.amount}/>
                                <Input onChange={this.handleElementChange} value={this.state.start_date} name='start_date' type='date'   labelSize='120px' label='Start Date'  errors={this.state.errors.start_date}/>
                                <Input onChange={this.handleElementChange} value={this.state.tenure}     name='tenure'     type='number' labelSize='120px' label='Tenure'      errors={this.state.errors.tenure}/>
                                <Input onChange={this.handleElementChange} value={this.state.interest}   name='interest'   type='text' labelSize='120px' label='Interest Rate' errors={this.state.errors.interest}/>
                                <LoanType onChange={this.handleLtpeChange} name='loan_type' selected={this.state.loan_type} labelSize='120px' errors={this.state.errors.loan_type} />
                                <Submit submitLabel='Save' cancelLabel='Cancel Edit' onCancel={this.handleCancel}/>
                            </form>
                        </div>
                    </Card>
                </div>
            </div>
        );
    }
}

const LoanEdit = connect(mapStateToProps, mapDispatchToProps)(ConnectedLoanEdit);
export default LoanEdit;