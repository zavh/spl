import React, { Component } from 'react';
import { connect } from "react-redux";
import { editFSData } from "../redux/actions/index";

function mapStateToProps (state)
{
  return { 
    fsdata: state.fsdata,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        editFSData: fsdata => dispatch(editFSData(fsdata)),
    };
}

class ConnectedFSConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            category:'',
            fsdata:{},
            ageerrors:[],
            slaberrors:[]
        }
        this.handleChangeAge = this.handleChangeAge.bind(this);
        this.handleSlabValChange = this.handleSlabValChange.bind(this);
        this.errorProcess = this.errorProcess.bind(this);
    }
    handleChangeAge(e){
        
        //COPYING
        let index = e.target.dataset.index;
        let category = this.state.category;
        var s = Object.assign({},this.props.fsdata[category].slab);
        var ua = [...this.props.fsdata[category].age]; //Updated Age array
        
        let orig = ua[index];
        //MUTATING COPIES
        ua[index] = e.target.value;
        let us = {}; //Updated Slab object
        for(var key in s){
            if(key == orig)
                us[e.target.value] = s[key];
            else 
                us[key] = s[key];
        }
        
        let update = {};
        update[category] = {};

        update[category]['age'] = ua;
        update[category]['slab'] = us;
        
        this.props.editFSData({update:update, key:category});
        if((e.target.value != 'any' && isNaN(e.target.value)) || e.target.value == ''){
            let errors = [...this.state.ageerrors]; 
            errors[0] = "'Age' should be either 'any' or a numeric value";
            this.setState({ageerrors:errors});
        }
        else {
            this.setState({ageerrors:[]});
        }
    }
    handleSlabValChange(e){
        //COPYING
        let index = e.target.dataset.index;
        let category = this.state.category;
        let s = Object.assign({},this.props.fsdata[category].slab);
        let a = [...this.props.fsdata[category].age];
        let age = a[index];
        s[a] = e.target.value;

        let update = {};
        update[category] = {};

        update[category]['age'] = a;
        update[category]['slab'] = s;
        this.props.editFSData({update:update, key:category});
        if(isNaN(e.target.value) || e.target.value == '' || e.target.value < 1){
            let errors = [...this.state.slaberrors]; 
            errors[0] = "'Slab' should be a non-zero numeric value";
            this.setState({slaberrors:errors});
        }
        else {
            this.setState({slaberrors:[]});
        }
    }
    componentDidUpdate(){
        if(this.state.category != this.props.category){
            this.setState({
                category:this.props.category,
            })
        }
    }
    errorProcess(){
        let a = [], g=[], errors = [];
        if(this.state.ageerrors.length > 0)
            a = [...this.state.ageerrors];
        if(this.state.slaberrors.length > 0)
            g = [...this.state.slaberrors];
        
        errors = a.concat(g);
        if(errors.length > 0){
            const divStyle = {
                display:'block',
              };
            return (
                <span className="invalid-feedback" role="alert" style={divStyle}>
                    {errors.map((e,i)=><strong key={i} className='mr-2'>▶{e}</strong>)}
                </span>
            )
        }
    }
    render(){
        
        let category = this.state.category;
        
        if(category == '')
            return null;
        else 
            return(
                <div>
                {this.props.fsdata[category].age.map((Age, index)=>{
                    return(
                        <div className="form-group row my-1" key={index}>
                            <div className="input-group input-group-sm col-md-12">
                                <div className="input-group-prepend">
                                    <span className="input-group-text"> Age </span>
                                </div>
                                <input type='text' className="form-control" value={Age} data-index={index} onChange={this.handleChangeAge}/>
                                
                                <div className="input-group-append">
                                    <span className="input-group-text">Slab Value</span>
                                </div>
                                <input type='number' className="form-control" value={this.props.fsdata[category].slab[Age]} data-index={index} onChange={this.handleSlabValChange}/>
                                {this.errorProcess()}
                            </div>
                        </div>
                    )
                })}
                </div>
            );
    }
}
const FSConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedFSConfig);
export default FSConfig;