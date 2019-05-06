import React, { Component } from 'react';
import Card from '../commons/Card';
import { connect } from "react-redux";
import SingleInput from './SingleInput';
import { setSchedule } from "./redux/actions/index";
import Submit from '../commons/submit'

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
            origschedule:{},
            panelAlert:{message:'',type:''},
        };
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.onInsert  = this.onInsert.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
        this.handleElementChange  = this.handleElementChange.bind(this);
        this.undoScheduleChanges = this.undoScheduleChanges.bind(this);
        this.refreshMessage = this.refreshMessage.bind(this);
    }
    refreshMessage(){
        this.setState({panelAlert:{message:'',type:''}});
    }
    onInsert(el){
        let loan = this.props.activeloans[this.props.match.params.index];
        let index=el.dataset.index;
        let amount = loan.params['Amount'];
        let tenure = loan.params['Tenure'];
        let schedule = Object.assign({}, this.props.schedule);
        
        let manual_flag = false;
        let count = 0;
        let installment = 0;
        for(var month in schedule){
            if(month != index && !manual_flag) {
                amount -= parseFloat(this.state[month].value);
                console.log('Month', month, 'Installment', this.state[month].value, 'Remaining', amount);
            }
            else{
                if(!manual_flag) {
                    manual_flag = true;
                    let x = 0;
                    let rschcount = 0;
                    for(var m in this.state.reschedulePoint){
                        x +=  parseFloat(this.state[m].value);
                        rschcount++;
                    }
                    installment = (amount - x) / (tenure - count - rschcount)
                    if(installment < 0){
                        this.undoScheduleChanges();
                        break;
                    }
                    console.log('Amount', amount, 'Reschedule amount', x, 'Rescheduler', rschcount)
                }
                if(month in this.state.reschedulePoint){
                    schedule[month] = this.state[month].value;
                    amount -= parseFloat(this.state[month].value);
                    console.log('Month', month, 'Installment', this.state[month].value, 'Remaining', amount);
                }
                else{
                    schedule[month] = installment;
                    amount -= installment;
                    console.log('Month', month, 'Installment', installment, 'Remaining', amount);
                }
            }
            count++;
            this.setState({[month]:{value:schedule[month], preventUpdate:true}});
        }
        if(Math.round(amount) != 0){
            this.undoScheduleChanges();
            this.setState({panelAlert:{message:'Error! Generating negative installment or incomplete schedule',type:'danger'}});
        }   
        else {
            this.setState({reschedulePoint:{}, saveFlag:true});
            this.props.setSchedule(schedule);
        }
    }

    undoScheduleChanges(){
        this.setState({reschedulePoint:{}, saveFlag:false});
        this.props.setSchedule(this.state.origschedule);
        for(var month in this.state.origschedule){
            this.setState({[month]:{value:this.state.origschedule[month], preventUpdate:true}});
        }
    }
    componentDidUpdate(prev){
        if(!this.state.scheduleReceived && this.props.schedule != prev.schedule){
            Object.keys(this.props.schedule).map(key=>{
                this.setState({[key]:{
                    value:this.props.schedule[key],
                    preventUpdate:true,
                }})
            })
            let os = Object.assign({},this.props.schedule);
            this.setState({scheduleReceived:true, origschedule:os});
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
    render() {
        return (
            <Card title='Loan Schedule'>
                <form onSubmit={this.handleSubmit}>
                    <div className='m-2' style={{maxHeight:'500px',overflow:'auto'}}>
                        { this.state.scheduleReceived &&
                            <React.Fragment>
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
                            </React.Fragment>
                        }
                        { !this.state.scheduleReceived && 
                            <div>Loading</div>
                        }
                    </div>
                    { this.state.saveFlag &&
                        <div className='mx-4 mt-1 mb-2'>
                            <Submit submitLabel='Save Schedule' cancelLabel='Undo Changes' onCancel={this.undoScheduleChanges}/>
                        </div>
                    }
                </form>
                <Alert alert={this.state.panelAlert} refreshMessage={this.refreshMessage}/>
            </Card>
        );
    }
}
const ScheduleEdit = connect(mapStateToProps, mapDispatchToProps)(ConnectedScheduleEdit);
export default ScheduleEdit;

function Alert(props){
    function refreshMessage(){
        props.refreshMessage();
    }
    var styleType = '';
    if(props.alert.type == '') return null;
    if(props.alert.type == 'warning')
        styleType = 'alert-warning'
    if(props.alert.type == 'danger')
        styleType = 'alert-danger'
    if(props.alert.type == 'success')
        styleType = 'alert-success'

    return(
        <div className={`alert ${styleType} mx-4 p-0`} role="alert">
            <div className='d-flex justify-content-between'>
                <span>{props.alert.message}</span>
                <button type="button" className="btn btn-sm " onClick={refreshMessage}>
                    <span>&times;</span>
                </button>
            </div>
        </div>
    );
}