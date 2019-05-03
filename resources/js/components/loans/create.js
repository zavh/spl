import React, { Component } from 'react';
import Departments from '../commons/Departments';
import Input from '../commons/Input';
import Card from '../commons/Card';
import Users from './Users';
import LoanType from './loantype';
import { connect } from "react-redux";
import { addActiveLoan } from "./redux/actions/index";
function mapStateToProps (state)
{
  return {
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        addActiveLoan: loan=> dispatch(addActiveLoan(loan)),
     };
}

class ConnectedCreate extends Component {
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
            createErrorState:false,
            users:[],
        };

        this.handleDeptChange = this.handleDeptChange.bind(this);
        this.handleElementChange = this.handleElementChange.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.clearErrorBag = this.clearErrorBag.bind(this);
        this.reset = this.reset.bind(this);
    }

    handleDeptChange(id){
        this.setState({
            department:id,
            salary_id:0
        });
        this.getUsers(id);
    }

    handleElementChange(name, value){
        this.setState({[name]:value});
    }

    handleSubmit(event){
        event.preventDefault();
        if(this.state.createErrorState)
            this.clearErrorBag();
        let status;
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
                console.log(response.data)
                this.setState({createErrorState:true});
                let e = response.data.errors;
                let errors = Object.assign({}, this.state.errors);
                for(var key in errors){
                    errors[key] =  key in e ? e[key]:[]
                }
                this.setState({errors});
            }
            else if(status == 'success'){
                this.props.addActiveLoan(response.data.loan);
                this.reset();
            }
            else if(status == 'experimenting'){
                console.log(response.data)
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
        if(this.state.createErrorState)
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
        this.setState({createErrorState:false});
        let errors = Object.assign({}, this.state.errors);
        for(var key in errors){
            errors[key] = [];
        }
        this.setState({errors});
    }

    getUsers(id){
        axios.get('/departments/users/'+id)
            .then((response)=>this.setState({
                users:response.data.users,
            })
        );
    }

    render() {
        return (
                <Card title='Create New Loan'>
                    <div className='m-2'>
                    <form onSubmit={this.handleSubmit}>
                        <Departments onChange={this.handleDeptChange} name='department' selected={this.state.department} labelSize='90px'/>
                        <Users onChange={this.handleElementChange} name='salary_id' department={this.state.department} users={this.state.users} selected={this.state.salary_id} labelSize='90px' errors={this.state.errors.salary_id}/>
                        <Input onChange={this.handleElementChange} value={this.state.loan_name}  name='loan_name'  type='text'   labelSize='90px' label='Loan title'  errors={this.state.errors.loan_name} />
                        <Input onChange={this.handleElementChange} value={this.state.amount}     name='amount'     type='number' labelSize='90px' label='Amount'      errors={this.state.errors.amount}/>
                        <Input onChange={this.handleElementChange} value={this.state.start_date} name='start_date' type='date'   labelSize='90px' label='Start Date'  errors={this.state.errors.start_date}/>
                        <Input onChange={this.handleElementChange} value={this.state.tenure}     name='tenure'     type='number' labelSize='90px' label='Tenure'      errors={this.state.errors.tenure}/>
                        <Input onChange={this.handleElementChange} value={this.state.interest}   name='interest'   type='text' labelSize='90px' label='Interest Rate' errors={this.state.errors.interest}/>
                        <LoanType onChange={this.handleElementChange} name='loan_type' selected={this.state.loan_type} labelSize='90px' errors={this.state.errors.loan_type} />
                        <Submit submitLabel='Submit' cancelLabel='Reset' onCancel={this.reset} salary_id={this.state.salary_id} />
                    </form>
                    </div>
                </Card>
        );
    }
}

const Create = connect(mapStateToProps, mapDispatchToProps)(ConnectedCreate);
export default Create;

function Submit(props){
    function handleCancel(){
        props.onCancel();
    }
    if(props.salary_id == 0){
        return null
    }
    else 
    return(
        <div className="row">
            <div className="col-6 m-0 pr-1"> 
                <input type="submit" className="btn btn-outline-primary btn-sm btn-block" value={props.submitLabel}/>
            </div>
            <div className="col-6 pl-1"> 
                <button type='button' className="btn btn-outline-dark btn-sm btn-block" onClick={handleCancel}>{props.cancelLabel}</button>
            </div>
        </div>
    );
}