import React, { Component } from 'react';
import Departments from '../commons/Departments';
import Input from '../commons/Input';
import Submit from '../commons/submit';
import Card from '../commons/Card';
import Users from './users';
import LoanType from './loantype';

export default class Create extends Component {
    constructor(props){
        super(props);
        this.state = {
            department:0,
            salary_id:0,
            loan_name:'',
            amount:'',
            start_date:'',
            tenure:'',
            interest:'',
            loan_type:'0',
            errors:{
                salary_id:[],
                loan_name:[],
                amount:[],
                start_date:[],
                tenure:[],
                interest:[],
                loan_type:[],
            },
            creaetErrorState:false,
            users:[],
        };

        this.handleDeptChange = this.handleDeptChange.bind(this);
        this.handleUserChange = this.handleUserChange.bind(this);
        this.handleNameChange = this.handleNameChange.bind(this);
        this.handleAmntChange = this.handleAmntChange.bind(this);
        this.handleDateChange = this.handleDateChange.bind(this);
        this.handleTenuChange = this.handleTenuChange.bind(this);
        this.handleIntrChange = this.handleIntrChange.bind(this);
        this.handleLtpeChange = this.handleLtpeChange.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.clearErrorBag = this.clearErrorBag.bind(this);
        this.reset = this.reset.bind(this);
    }

    handleDeptChange(id){
        this.setState({department:id});
        this.getUsers(id);
    }

    handleUserChange(id){
        this.setState({salary_id:id});
    }

    handleNameChange(value){
        this.setState({loan_name:value});
    }

    handleAmntChange(value){
        this.setState({amount:value});
    }

    handleDateChange(value){
        this.setState({start_date:value});
    }

    handleTenuChange(value){
        this.setState({tenure:value});
    }

    handleIntrChange(value){
        this.setState({interest:value});
    }

    handleLtpeChange(value){
        this.setState({loan_type:value});
    }

    handleSubmit(event){
        event.preventDefault();
        if(this.state.creaetErrorState)
            this.clearErrorBag();
        let status = 'success';
        axios.post('/loans', {
            salary_id:this.state.salary_id,
            loan_name:this.state.loan_name,
            amount:this.state.amount,
            start_date:this.state.start_date,
            tenure:this.state.tenure,
            interest:this.state.interest,
            loan_type:this.state.loan_type,
          })
          .then((response)=>{
            status = response.data.status;
            if(status == 'failed'){
                this.setState({creaetErrorState:true});
                let e = response.data.errors;
                let errors = Object.assign({}, this.state.errors);
                for(var key in errors){
                    errors[key] =  key in e ? e[key]:[]
                }
                this.setState({errors});
            }
            else if(status == 'success'){
                this.props.bridge(response.data.loan);
                this.reset();
            }
          })
          .catch(function (error) {
            console.log(error);
          });
    }

    handleCancel(event){
        event.preventDefault();
        this.reset();
    }

    reset(){
        if(this.state.creaetErrorState)
            this.clearErrorBag();
        this.setState(
            {
                department:0,
                salary_id:0,
                loan_name:'',
                amount:'',
                start_date:'',
                tenure:'',
                interest:'',
                loan_type:0
            }
        );
    }
    clearErrorBag(){
        this.setState({creaetErrorState:false});
        let errors = Object.assign({}, this.state.errors);
        for(var key in errors){
            errors[key] = [];
        }
        this.setState({errors});
    }

    getUsers(id){
        axios.get('/departments/users/'+id)
            .then((response)=>this.setState({
                users:[...response.data.users]
            })
        );
    }

    render() {
        return (
                <Card title='Create New Loan'>
                    <div className='m-2'>
                    <form onSubmit={this.handleSubmit}>
                        <Departments onChange={this.handleDeptChange} name='department' selected={this.state.department} labelSize='90px'/>
                        <Users onChange={this.handleUserChange} name='users' department={this.state.department} users={this.state.users} selected={this.state.salary_id} labelSize='90px' errors={this.state.errors.salary_id}/>
                        <Input onChange={this.handleNameChange} value={this.state.loan_name}  name='loan_name'  type='text'   labelSize='90px' label='Loan title'  errors={this.state.errors.loan_name}/>
                        <Input onChange={this.handleAmntChange} value={this.state.amount}     name='amount'     type='number' labelSize='90px' label='Amount'      errors={this.state.errors.amount}/>
                        <Input onChange={this.handleDateChange} value={this.state.start_date} name='start_date' type='date'   labelSize='90px' label='Start Date'  errors={this.state.errors.start_date}/>
                        <Input onChange={this.handleTenuChange} value={this.state.tenure}     name='tenure'     type='number' labelSize='90px' label='Tenure'      errors={this.state.errors.tenure}/>
                        <Input onChange={this.handleIntrChange} value={this.state.interest}   name='interest'   type='text' labelSize='90px' label='Interest Rate' errors={this.state.errors.interest}/>
                        <LoanType onChange={this.handleLtpeChange} name='loan_type' selected={this.state.loan_type} labelSize='90px' errors={this.state.errors.loan_type} />
                        <Submit submitLabel='Submit' cancelLabel='Reset' onCancel={this.handleCancel}/>
                    </form>
                    </div>
                </Card>
        );
    }
}