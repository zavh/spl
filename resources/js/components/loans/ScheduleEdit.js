import React, { Component } from 'react';
import Card from '../commons/Card';
import { connect } from "react-redux";
import SingleInput from './SingleInput';
import { setSchedule } from "./redux/actions/index";

function mapStateToProps (state)
{
  return {
      schedule: state.schedule,
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setSchedule: loans=> dispatch(setSchedule(loans)),
     };
}

class ConnectedScheduleEdit extends Component {
    
    constructor(props){
        super(props);
        this.state = {
            scheduleReceived:false,
            errors:{},
            reschedulePoint:{},
            saveFlag:false,
        };
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.onInsert  = this.onInsert.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
        this.handleElementChange  = this.handleElementChange.bind(this);
        // this.scrollToBottom  = this.scrollToBottom.bind(this);
    }
    
    onInsert(el){
        let loan = this.props.activeloans[this.props.match.params.index];
        let index=el.dataset.index;
        let amount = loan.params['Amount'];
        let tenure = loan.params['Tenure'];
        let schedule = Object.assign({}, this.props.schedule);
        
        let manual_flag = false;
        let count = 0;
        let installmnt = 0;
        for(var month in schedule){
            if(month != index && !manual_flag) {
                amount -= this.state[month].value;
            }
            else{
                if(!manual_flag) manual_flag = true;
                if(month in this.state.reschedulePoint){
                    schedule[month] = this.state[month].value;
                    amount -= this.state[month].value;
                }
                else{
                    if(tenure-count != 0){
                        installmnt = amount/(tenure-count);
                        schedule[month] = installmnt;
                        amount -= installmnt;
                    }
                    else {
                        schedule[month] = amount;
                    }
                }
            }
            count++;
            this.setState({[month]:{value:schedule[month], preventUpdate:true}});
        }
        this.props.setSchedule(schedule);
        this.setState({reschedulePoint:{}, saveFlag:true});
        // this.scrollToBottom(ref);
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

    componentWillUnmount(){
        this.props.setSchedule({});
    }
    handleElementChange(name, value){
        if(isNaN(value)){
            this.setState({errors:{[name]:['Schedule value must be a number']}})
            return;
        }
        else {
            this.setState({errors:{[name]:[]}})
        }
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
        axios.post(`/loans/scheduleupdate/${this.props.match.params.id}`, {
            schedule: JSON.stringify(this.props.schedule),
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
            else 
                console.log(response.data);
          })
          .catch(function (error) {
            console.log(error);
          });
    }

    handleCancel(e){
        e.preventDefault();
        this.props.history.push('/loans');
    }
    // scrollToBottom(ref) {
    //     ref.scrollIntoView({ behavior: 'smooth' })
    //   }
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
                                        labelSize='110px'
                                        label= {<React.Fragment><span>{key}</span> <span className='badge badge-pill badge-light border shadow-sm mx-2'> {index + 1}</span></React.Fragment>}
                                        errors={this.state.errors[key]}
                                        onInsert={this.onInsert}
                                        actionButton='Re-Schedule This'
                                        preventUpdate={this.state[key]['preventUpdate']}
                                        />
                                    )
                                })}
                                { this.state.saveFlag &&
                                    <button type="submit" className="btn btn-primary btn-sm btn-block" onClick={this.handleSubmit}>Save</button>
                                }
                        </form>
                    }
                    { !this.state.scheduleReceived && 
                        <div>Loading</div>
                    }
                </div>
            </Card>
        );
    }
}
const ScheduleEdit = connect(mapStateToProps, mapDispatchToProps)(ConnectedScheduleEdit);
export default ScheduleEdit;