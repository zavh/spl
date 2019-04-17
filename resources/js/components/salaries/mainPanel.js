import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from './UploadMonthData';
import MonthSelect from './monthSelect';
import Departments from '../commons/Departments';
import { connect } from "react-redux";
import { setMainPanel, setEmployee, setPayYear, setSalaryRows } from "./redux/actions/index";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import SalaryOutput from './SalaryOutput';
function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      salaryrows: state.salaryrows,
      timeline: state.timeline,
      reftimeline: state.reftimeline,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setEmployee: employee=> dispatch(setEmployee(employee)),
        setPayYear: timeline=> dispatch(setPayYear(timeline)),
        setSalaryRows: salaryrows=> dispatch(setSalaryRows(salaryrows)),
    };
}
class ConnectedMainPanel extends Component {
    constructor(props){
        super(props);
        this.state = {
            status:'success',
            allowupload:true,
            message:'',
            errors:{
                year:[],
            },
            modal:false,
        }
        this.handleMonthChange = this.handleMonthChange.bind(this);
        this.handleYearChange = this.handleYearChange.bind(this);
        this.handleTimelineChange = this.handleTimelineChange.bind(this);
        this.toggle = this.toggle.bind(this);
    }

    toggle() {
        this.setState(prevState => ({
          modal: !prevState.modal
        }));
      }

    handleMonthChange(month){
        let timeline = {
            fromYear:this.props.timeline.fromYear,
            toYear:this.props.timeline.toYear,
            month: parseInt(month),
        };
        this.props.setPayYear(timeline);
        if(timeline.month>0)
            this.handleTimelineChange(timeline.fromYear, timeline.month);
    }

    handleYearChange(e){
        let timeline = {
            fromYear:parseInt(e.target.value),
            toYear:parseInt(e.target.value) + 1,
            month: 0,
        };
        this.props.setPayYear(timeline);
        (timeline.fromYear != this.props.reftimeline.fromYear)?this.setState({allowupload:false}):this.setState({allowupload:true});
    }

    handleTimelineChange(fromYear=this.props.timeline.fromYear, month=this.props.timeline.month){
        axios.get(`/salaries/dbcheck/${fromYear}-${month}`)
        .then(
            (response)=>{
                console.log(response);
                status = response.data.status;
                this.setState({status:status})
                if(status === 'success'){
                    this.props.setSalaryRows(response.data.data);
                }
                else if(status === 'fail'){
                    this.setState({
                        message:response.data.message,
                    });
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }

    componentDidMount(){
        this.setState({
            status:this.props.status,
        });
    }
    render(){
        var Output;

        return(
            <div>
                <div className="form-group row my-1 small">
                    <div className="input-group input-group-sm col-md-6">
                        <div className="input-group-prepend">
                            <span className="input-group-text">Choose Year</span>
                        </div>
                        <input type='number' className="form-control" onChange={this.handleYearChange} value={this.props.timeline.fromYear} />
                        <div className="input-group-append">
                            <span className="input-group-text">Choose Month</span>
                        </div>
                        <MonthSelect fromYear={this.props.timeline.fromYear} toYear={this.props.timeline.toYear} month={this.props.timeline.month} onChange={this.handleMonthChange}/>
                    </div>
                    <FileUpload status={this.state.allowupload} timeline={this.props.timeline} onFnishing={this.handleTimelineChange}/>
                    <div className='col-md-2'>
                        <Button color="danger" onClick={this.toggle} className="btn btn-sm">Filters</Button>
                        <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                        <ModalHeader toggle={this.toggle}>Modal title</ModalHeader>
                        <ModalBody>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </ModalBody>
                        <ModalFooter>
                            <Button color="primary" onClick={this.toggle}>Do Something</Button>{' '}
                            <Button color="secondary" onClick={this.toggle}>Cancel</Button>
                        </ModalFooter>
                        </Modal>
                    </div>
                </div>
                <SalaryOutput fromYear={this.props.timeline.fromYear} />
            </div>
        )
    }
}

function YearNotification(props){
    return(
      <div className='small'>Year {props.fromYear} to {props.toYear} salaries wil be shown</div>
    ) 
}

const MainPanel = connect(mapStateToProps, mapDispatchToProps)(ConnectedMainPanel);
export default MainPanel;
