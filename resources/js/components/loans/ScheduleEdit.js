import React, { Component } from 'react';
import Card from '../commons/Card';
import { connect } from "react-redux";
import SingleInput from './SingleInput';
import { setSchedule, setStickyness } from "./redux/actions/index";
import Submit from '../commons/submit';
import { C401, C402 } from "./codes/index";

function mapStateToProps (state)
{
  return {
      schedule: state.schedule,
      activeloans: state.activeloans,
      stickyness: state.stickyness,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setSchedule: loans=> dispatch(setSchedule(loans)),
        setStickyness: stickyness=> dispatch(setStickyness(stickyness)),
     };
}

class ConnectedScheduleEdit extends Component {
    
    constructor(props){
        super(props);
        this.state = {
            scheduleReceived:false,
            sticknessReceived:false,
            errors:{},
            elementErrorState:false,
            reschedulePoint:{},
            saveFlag:false,
            origschedule:{},
            origstickyness:{},
            panelAlert:{message:'',type:''},
        };
        this.handleSubmit  = this.handleSubmit.bind(this);
        this.onInsert  = this.onInsert.bind(this);
        this.handleCancel  = this.handleCancel.bind(this);
        this.handleElementChange  = this.handleElementChange.bind(this);
        this.undoScheduleChanges = this.undoScheduleChanges.bind(this);
        this.refreshMessage = this.refreshMessage.bind(this);
        this.stickyChange = this.stickyChange.bind(this);
    }
    refreshMessage(){
        this.setState({panelAlert:{message:'',type:''}});
    }

    onInsert(el){
        let loan = this.props.activeloans[this.props.match.params.index];
        let amount = loan.amount;
        let stickyamount = 0;
        let unstickycount = 0;
        let unstickyels = [];
        let schedule = {};
        let stickyness = {};
        for(var month in this.props.schedule){
            if(this.state[month].stickyness)
                stickyamount += parseFloat(this.state[month].value);
            else {
                unstickyels[unstickycount] = month;
                unstickycount++;
            }
            schedule[month] = parseFloat(this.state[month].value);
            stickyness[month] = this.state[month].stickyness;
        }
        let installment = (amount - stickyamount)/unstickycount;
        if((installment * unstickycount + stickyamount) != amount || installment < 0){
            this.undoScheduleChanges();
            this.setState({panelAlert:{message:C402,type:'danger'}});
        }
        else{
            for(var i=0;i<unstickycount;i++){
                this.setState({
                    [unstickyels[i]]:{value:installment,preventUpdate:true,stickyness:false}
                });
                schedule[unstickyels[i]] = installment;
            }
            let buttonremover = {value:this.state[el.dataset.index].value, preventUpdate:true, stickyness:true};
            this.setState({[el.dataset.index]:buttonremover, saveFlag:true, reschedulePoint:{}});
            this.props.setSchedule(schedule);
            this.props.setStickyness(stickyness);
            console.log('Unsticky Count', unstickycount, 'Sticky Amount', stickyamount, 'Installment', installment);
        }
    }

    stickyChange(index, value){
        this.setState({
            [index]:{
                value:this.state[index].value,
                stickyness:value,
                preventUpdate:this.state[index].preventUpdate,
            }
        });
    }
    undoScheduleChanges(){
        this.setState({reschedulePoint:{}, saveFlag:false});
        this.props.setSchedule(this.state.origschedule);
        for(var month in this.state.origschedule){
            this.setState({[month]:{value:this.state.origschedule[month], preventUpdate:true, stickyness:this.state.origstickyness[month]}});
        }
    }
    componentDidUpdate(prev){
        if(!this.state.scheduleReceived && this.props.schedule != prev.schedule){
            Object.keys(this.props.schedule).map(key=>{
                this.setState({[key]:{
                    stickyness:true,
                    value:this.props.schedule[key],
                    preventUpdate:true,
                }})
            })
            let os = Object.assign({},this.props.schedule);
            this.setState({scheduleReceived:true, origschedule:os});
        }
        if(!this.state.sticknessReceived && this.props.stickyness != prev.stickyness){
            Object.keys(this.props.stickyness).map(key=>{
                this.setState({[key]:{
                    stickyness:this.props.stickyness[key],
                    value:this.props.schedule[key],
                    preventUpdate:true,
                }})
            })
            let os = Object.assign({},this.props.stickyness);
            this.setState({sticknessReceived:true, origstickyness:os});
        }
    }

    componentWillUnmount(){
        this.props.setSchedule({});
        this.props.setStickyness({});
    }

    handleElementChange(name, value){
        if(isNaN(value) || value < 0){
            this.setState({errors:{[name]:[C401]}, elementErrorState:true})
            return;
        }
        else if(this.state.elementErrorState){
                this.setState({errors:{}, elementErrorState:false})
        }
        let update = {};
        let reschedulePoint = {};
        if(this.props.schedule[name] != value){ //value different, append the element in reschedulePoint
            update = {value:value, preventUpdate:true, stickyness:this.state[name].stickyness};
            if(!(name in this.state.reschedulePoint)){
                reschedulePoint = Object.assign({},this.state.reschedulePoint, {[name]:name})
            }
            else 
                reschedulePoint = Object.assign({},this.state.reschedulePoint)
        }
        else { //Value change reverted, remove the element from reschedulePoint
            update = {value:value,preventUpdate:true,stickyness:this.props.stickyness[name]};
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
                this.setState({[sched]:{value:this.state[sched].value,preventUpdate:true, stickyness:true}})
            }
            else {
                this.setState({[sched]:{value:value,preventUpdate:true, stickyness:true}})
            }
            if(lowest == '' || lowest > sched){
                lowest = sched
            }
        }
        if(lowest == '') console.log('No reschedule point exists');
        else {
            if(lowest != name){
                this.setState({[lowest]:{value:this.state[lowest].value,preventUpdate:false, stickyness:true}})
            }
            else {
                this.setState({[lowest]:{value:value,preventUpdate:false, stickyness:true}})
            }
        }
    }

    handleSubmit(e){
        e.preventDefault();
        axios.post(`/loans/scheduleupdate/${this.props.match.params.id}`, {
            schedule: JSON.stringify(this.props.schedule),
            stickyness: JSON.stringify(this.props.stickyness)
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
                    <div style={{maxHeight:'500px',overflow:'auto'}}>
                        { this.state.scheduleReceived &&
                            <React.Fragment>
                                {Object.keys(this.props.schedule).map((key,index)=>{
                                    return(
                                        <SingleInput 
                                            key={index}
                                            onChange={this.handleElementChange}
                                            value={this.state[key]['value']}
                                            name={key} type='text'
                                            labelSize='100px'
                                            label= {<React.Fragment><span>{key}</span> <span className='badge badge-pill badge-light border shadow-sm mx-2'> {index + 1}</span></React.Fragment>}
                                            errors={this.state.errors[key]}
                                            onInsert={this.onInsert}
                                            actionButton='Re-Schedule This'
                                            preventUpdate={this.state[key]['preventUpdate']}
                                            stickyness={this.state[key]['stickyness']}
                                            stickyChange={this.stickyChange}
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
                <span className='p-2'>{props.alert.message}</span>
                <button type="button" className="btn btn-sm " onClick={refreshMessage}>
                    <span>&times;</span>
                </button>
            </div>
        </div>
    );
}