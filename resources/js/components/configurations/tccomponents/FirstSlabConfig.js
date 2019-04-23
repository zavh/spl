import React, { Component } from 'react';
import { connect } from "react-redux";
import { editFSAge, editFSSlab } from "../redux/actions/index";

function mapStateToProps (state)
{
  return { 
    fsdata: state.fsdata,
    fserrors: state.fserrors,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        editFSAge: fsdata => dispatch(editFSAge(fsdata)),
        editFSSlab: fsdata => dispatch(editFSSlab(fsdata)),
    };
}

class ConnectedFSConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            category:'',
            fsdata:{},
            ageerrors:[],
            slaberrors:[],
        }
        this.handleChangeAge = this.handleChangeAge.bind(this);
        this.handleChangeSlab = this.handleChangeSlab.bind(this);
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
        
        this.props.editFSAge({update:update, index:index, value:e.target.value, category:category});
    }
    handleChangeSlab(e){
        //COPYING
        let index = e.target.dataset.index;
        let category = this.state.category;
        let s = Object.assign({},this.props.fsdata[category].slab);
        let a = [...this.props.fsdata[category].age];
        let age = a[index];
        s[age] = e.target.value;

        let update = {};
        update[category] = {};

        update[category]['age'] = a;
        update[category]['slab'] = s;
        this.props.editFSSlab({update:update, key:category});
    }
    componentDidUpdate(){
        if(this.state.category != this.props.category){
            this.setState({
                category:this.props.category,
            })
        }
    }
    errorProcess(index){
        // let a = [], g=[], errors = [];
        // if(this.state.ageerrors.length > 0)
        //     a = [...this.state.ageerrors];
        // if(this.state.slaberrors.length > 0)
        //     g = [...this.state.slaberrors];
        if(this.props.fserrors[index] === undefined)
            return;        
        let errors = this.props.fserrors[index];

        if(errors != ''){
            const divStyle = {
                display:'block',
              };
            return (
                <span className="invalid-feedback" role="alert" style={divStyle}>
                    {/* {errors.map((e,i)=><strong key={i} className='mr-2'>▶{e}</strong>)} */}
                    <strong className='mr-2'>▶{errors}</strong>
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
                                <input type='number' className="form-control" value={this.props.fsdata[category].slab[Age]} data-index={index} onChange={this.handleChangeSlab}/>
                                {this.errorProcess(index)}
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