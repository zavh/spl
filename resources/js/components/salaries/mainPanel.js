import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from './UploadMonthData';
import MonthSelect from './monthSelect';

import { connect } from "react-redux";
import { setMainPanel, setEmployee, setPayYear, setSalaryRows, setIndexing } from "./redux/actions/index";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import SalaryOutput from './SalaryOutput';
function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      salaryrows: state.salaryrows,
      timeline: state.timeline,
      reftimeline: state.reftimeline,
      indexing: state.indexing,
      filters: state.filters,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setEmployee: employee=> dispatch(setEmployee(employee)),
        setPayYear: timeline=> dispatch(setPayYear(timeline)),
        setSalaryRows: salaryrows=> dispatch(setSalaryRows(salaryrows)),
        setIndexing: indexing=>dispatch(setIndexing(indexing)),
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
            filteredrows:[],
            validtimeline:{},
        }
        this.handleMonthChange = this.handleMonthChange.bind(this);
        this.handleYearChange = this.handleYearChange.bind(this);
        this.handleTimelineChange = this.handleTimelineChange.bind(this);
        this.handleFilterChange = this.handleFilterChange.bind(this);
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
        if(timeline.month>0){
            this.handleTimelineChange(timeline.fromYear, timeline.month);
            (timeline.fromYear != this.props.reftimeline.fromYear)?this.setState({allowupload:false}):this.setState({allowupload:true});
        }
            
    }

    handleYearChange(e){
        let timeline = {
            fromYear:parseInt(e.target.value),
            toYear:parseInt(e.target.value) + 1,
            month: 0,
        };
        this.props.setPayYear(timeline);
        this.setState({allowupload:false});
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
                    this.props.setIndexing(response.data.indexing);
                    this.setState({
                        validtimeline:this.props.timeline,
                    });
                    console.log(this.props.salaryrows);
                    this.handleFilterChange(this.props.filters)
                }
                else if(status === 'fail'){
                    // If db table for target month does not exist, set the current timeline same as the last valid timeline
                    this.props.setPayYear(this.state.validtimeline);
                    this.setState({
                        message:response.data.message,
                        allowupload:true,
                    });
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }
    handleFilterChange(filters){
        if(filters.department == 0 && filters.pay_out_mode == 0){
            this.setState({
                filteredrows:this.props.salaryrows,
            });
        }
        else {
            let rows = this.props.salaryrows;
            let result = [];
            if(filters.department > 0){
                let indexing = this.props.indexing[filters.department];
                let resultb = [], resultc = [], i;
                for(i=0;i<indexing['bank'].length;i++){
                    resultb[i] = rows[indexing['bank'][i]];
                }
                for(i=0;i<indexing['cash'].length;i++){
                    resultc[i] = rows[indexing['cash'][i]];
                }
                if(filters.pay_out_mode==0)
                    result = resultb.concat(resultc);
                else if(filters.pay_out_mode == 'bank')
                    result = resultb;
                else if(filters.pay_out_mode == 'cash')
                    result = resultc;
            }
            else {
                let indexing = this.props.indexing, i=0;
                for (var k in indexing){
                    if (indexing.hasOwnProperty(k)) {
                        let pom = indexing[k][filters.pay_out_mode];
                        for(var j=0;j<pom.length;j++)
                         result[i++] = rows[pom[j]];
                    }
                }
            }
            
            this.setState({
                filteredrows:result,
            });            
        }

        // console.log(result);
    }
    componentDidMount(){
        this.setState({
            status:this.props.status,
            filteredrows:this.props.salaryrows,
            validtimeline:this.props.timeline,
        });
        this.handleFilterChange(this.props.filters);
    }
    render(){
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
                    {/* <div className='col-md-2'>
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
                    </div> */}
                </div>
                <SalaryOutput timeline={this.state.validtimeline} salaryrows={this.state.filteredrows} handleFilterChange={this.handleFilterChange}/>
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
