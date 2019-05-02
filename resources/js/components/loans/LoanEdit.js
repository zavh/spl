import React, { Component } from 'react';
import Input from '../commons/Input';
import Submit from '../commons/submit';
import LoanType from './loantype';
import Readonly from '../commons/Readonly';
import axios from 'axios';
import Card from '../commons/Card';
import { connect } from "react-redux";
// import {  } from "./redux/actions/index";

function mapStateToProps (state)
{
  return {
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        // setActiveLoans: loans=> dispatch(setActiveLoans(loans)),
     };
}
class ConnectedLoanEdit extends Component {
    constructor(props){
        super(props);
        this.state = {
            user:'',
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
        };
        this.handleNameChange  = this.handleNameChange.bind(this);
        this.handleAmntChange  = this.handleAmntChange.bind(this);
        this.handleDateChange  = this.handleDateChange.bind(this);
        this.handleTenuChange  = this.handleTenuChange.bind(this);
        this.handleIntrChange  = this.handleIntrChange.bind(this);
        this.handleLtpeChange  = this.handleLtpeChange.bind(this);
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
    }

    getLoan(){
        axios.get('/loans/'+this.props.match.params.id+'/edit')
            .then((response)=>this.setState({
                user:response.data.name,
                department:response.data.department,
                loan_name:response.data.data.loan_name,
                amount:response.data.data.amount,
                start_date:response.data.data.start_date,
                tenure:response.data.data.tenure,
                interest:response.data.data.interest,
                loan_type:response.data.data.loan_type,
            })
        );
    }
    
    componentDidMount(){
        this.getLoan();
    }

    handleNameChange(value){
        this.setState({loan_name:value})
    }

    handleAmntChange(value){
        this.setState({amount:value})
    }

    handleDateChange(value){
        this.setState({start_date:value})
    }

    handleTenuChange(value){
        this.setState({tenure:value})
    }

    handleIntrChange(value){
        this.setState({interest:value})
    }

    handleLtpeChange(value){
        this.setState({loan_type:value})
    }

    handleSubmit(e){
        e.preventDefault();
        if(this.state.editErrorState)
            this.clearErrorBag();
        let status = 'success';
        axios.put(`/loans/${this.props.id}`, {
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
                this.props.bridge(response.data.loan);
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
            <Card title='Edit Loan'>
            <div className='m-2'>
                <Readonly labelSize='90px' label='Department' value={this.state.department}/>
                <Readonly labelSize='90px' label='Employee' value={this.state.user}/>
                <form onSubmit={this.handleSubmit}>
                    <Input onChange={this.handleNameChange} value={this.state.loan_name}  name='loan_name'  type='text'   labelSize='90px' label='Loan title'  errors={this.state.errors.loan_name}/>
                    <Input onChange={this.handleAmntChange} value={this.state.amount}     name='amount'     type='number' labelSize='90px' label='Amount'      errors={this.state.errors.amount}/>
                    <Input onChange={this.handleDateChange} value={this.state.start_date} name='start_date' type='date'   labelSize='90px' label='Start Date'  errors={this.state.errors.start_date}/>
                    <Input onChange={this.handleTenuChange} value={this.state.tenure}     name='tenure'     type='number' labelSize='90px' label='Tenure'      errors={this.state.errors.tenure}/>
                    <Input onChange={this.handleIntrChange} value={this.state.interest}   name='interest'   type='text' labelSize='90px' label='Interest Rate' errors={this.state.errors.interest}/>
                    <LoanType onChange={this.handleLtpeChange} name='loan_type' selected={this.state.loan_type} labelSize='90px' errors={this.state.errors.loan_type} />
                    <Submit submitLabel='Save' cancelLabel='Cancel Edit' onCancel={this.handleCancel}/>
                </form>
            </div>
            </Card>
        );
    }
}

const LoanEdit = connect(mapStateToProps, mapDispatchToProps)(ConnectedLoanEdit);
export default LoanEdit;