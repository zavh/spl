import React, { Component } from 'react';
import Card from '../commons/Card';
import { connect } from "react-redux";
import SingleInput from './SingleInput';
// import {  } from "./redux/actions/index";

function mapStateToProps (state)
{
  return {
      schedule: state.schedule,
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        // modActiveLoan: loans=> dispatch(modActiveLoan(loans)),
        // setSchedule: loans=> dispatch(setSchedule(loans)),
     };
}
class ConnectedScheduleEdit extends Component {
    constructor(props){
        super(props);
        this.state = {
            scheduleReceived:false,
            errors:{},
            reschedulePoint:{},
        };
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.onInsert  = this.onInsert.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
        this.handleElementChange  = this.handleElementChange.bind(this);
    }

    onInsert(el){
        this.props.activeloans[this.props.match.params.index];
        let index=el.dataset.index;
        console.log(this.state[index])
    }

    componentWillUnmount(){
        // this.props.setSchedule({});
    }
    componentDidUpdate(prev){
        if(!this.state.scheduleReceived && this.props.schedule != prev.schedule){
            Object.keys(this.props.schedule).map(key=>{
                this.setState({[key]:{
                    value:this.props.schedule[key],
                    preventUpdate:true,
                }})
            })
            this.setState({scheduleReceived:true});
        }
    }
    handleElementChange(name, value){
        let update = {};
        let reschedulePoint = {};
        if(this.props.schedule[name] != value){ //value different, append the element in reschedulePoint
            update = {value:value, preventUpdate:true};
            if(!(name in this.state.reschedulePoint)){
                reschedulePoint = Object.assign({},this.state.reschedulePoint, {[name]:name})
            }
            else 
                reschedulePoint = Object.assign({},this.state.reschedulePoint)
        }
        else { //Value change reverted, remove the element from reschedulePoint
            update = {value:value,preventUpdate:true};
            for(var key in this.state.reschedulePoint)
                if(name != key)
                    reschedulePoint[key] = key;
        }
        this.setState({
            [name]:update,
            reschedulePoint:reschedulePoint
        });

        let lowest = '';
        // console.log(reschedulePoint);
        for(var sched in reschedulePoint){
            if(sched != name){
                this.setState({[sched]:{value:this.state[sched].value,preventUpdate:true}})
            }
            else {
                this.setState({[sched]:{value:value,preventUpdate:true}})
            }
            if(lowest == '' || lowest > sched){
                lowest = sched
            }
        }
        if(lowest == '') console.log('No reschedule point exists');
        else {
            if(lowest != name){
                this.setState({[lowest]:{value:this.state[lowest].value,preventUpdate:false}})
            }
            else {
                this.setState({[lowest]:{value:value,preventUpdate:false}})
            }
        }
    }

    handleSubmit(e){
        e.preventDefault();
        axios.put(`/loans/${this.props.match.params.id}`, {
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
            <Card title='Loan Schedule'>
            <div className='m-2' style={{maxHeight:'500px',overflow:'auto'}}>
                { this.state.scheduleReceived &&
                    <form onSubmit={this.handleSubmit} className='mx-4'>
                        {Object.keys(this.props.schedule).map((key,index)=>{
                            return(
                                <SingleInput 
                                    key={index}
                                    onChange={this.handleElementChange}
                                    value={this.state[key]['value']}
                                    name={key} type='text'
                                    labelSize='90px'
                                    label={key}
                                    errors={this.state.errors}
                                    onInsert={this.onInsert}
                                    actionButton='Re-Schedule This'
                                    preventUpdate={this.state[key]['preventUpdate']}
                                    />
                            )
                        })}
                    </form>
                }
                { !this.state.scheduleReceived && 
                    <div>Loading</div>
                }
            </div>
            </Card>
        );
    //   else 
        // return();
    }
}

const ScheduleEdit = connect(mapStateToProps, mapDispatchToProps)(ConnectedScheduleEdit);
export default ScheduleEdit;