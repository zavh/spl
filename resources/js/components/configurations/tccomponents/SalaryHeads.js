import React, { Component } from 'react';
import SingleInput from '../../commons/SingleInput';
import HeadConfig from './HeadConfig';
import {SaveButton} from './SaveButton';
import { connect } from "react-redux";
import { addSalaryHead, setSalaryHead, setHeadInitiation, setHeadSaveFlag} from "../redux/actions/index";

function mapStateToProps (state)
{
  return { 
    salaryheads: state.salaryheads,
    headinitiated: state.headinitiated,
    headneedsaving: state.headneedsaving,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        addSalaryHead: head => dispatch(addSalaryHead(head)),
        setSalaryHead: heads => dispatch(setSalaryHead(heads)),
        setHeadInitiation: flag => dispatch(setHeadInitiation(flag)),
        setHeadSaveFlag: flag => dispatch(setHeadSaveFlag(flag)),
    };
}
class ConnectedSalaryHeads extends Component{
    constructor(props){
        super(props);
        this.state = {
            newHead : '',
            errors:[],
        }
        this.handleAddHead = this.handleAddHead.bind(this);
        this.onInsert = this.onInsert.bind(this);
        this.saveConfig = this.saveConfig.bind(this);
    }
    handleAddHead(value){
        this.setState({
            newHead:value,
        });
    }
    onInsert(){
        let value = this.state.newHead;
        let key = '';
        let  errors = [];
        let head = {};
        if(value == ''){
            errors[0]="No value for 'Head' was found";
            this.setState({errors: errors,});
            return;
        }
        else {
            key = value.replace(' ','_').toLowerCase();
            this.setState({errors: errors,})
        }

        if(key in this.props.salaryheads){
            errors[0]= value + " already exists";
            return;
        }
        else{
            head[key] = value;
            this.props.addSalaryHead(head);
            this.setState({newHead:''})
        }
    }
    saveConfig(){
        console.log(this.props.salaryheads);
    }
    componentDidMount(){
        this.props.setHeadInitiation(true);
    }
    componentDidUpdate(prev){
        if( this.props.headinitiated && this.props.salaryheads != prev.salaryheads)
            this.props.setHeadSaveFlag(true)
    }
    render(){
        return(
            <div className='container-fluid'>
                <div className='row'>
                    <div className='col-md-3'>
                    <SingleInput 
                        labelSize = '100'
                        label = 'Add a Head'
                        type = 'text'
                        onChange = {this.handleAddHead}
                        value = {this.state.newHead}
                        errors = {this.state.errors}
                        onInsert={this.onInsert}
                    />
                    </div>
                    <div className='col-md-3 my-1'>
                    <SaveButton onClick={this.saveConfig} needssaving={this.props.headneedsaving}/>
                    </div>
                </div>
                {Object.keys(this.props.salaryheads).map((key)=>{
                    return(
                        <HeadConfig key={key} p={this.props.salaryheads[key]} u={key}/>
                    )
                })}
            </div>
        );
    }
}

const SalaryHeads = connect(mapStateToProps, mapDispatchToProps)(ConnectedSalaryHeads);
export default SalaryHeads;