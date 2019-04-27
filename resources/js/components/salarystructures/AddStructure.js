import React, { Component } from 'react';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { connect } from "react-redux";
import { setConfig, setCurrent, modStructure} from "./redux/actions/index";

function mapStateToProps (state)
{
  return {
      config: state.config,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setConfig: structure => dispatch(setConfig(structure)),
        setCurrent: index => dispatch(setCurrent(index)),
        modStructure: structure => dispatch(modStructure(structure)),
     };
}
class ConnectedAddStructure extends Component{
    constructor(props){
        super(props);
        this.state = {
            loaderror: '',
        }
        this.handleCancel = this.handleCancel.bind(this);
        this.handleValueTypeChange = this.handleValueTypeChange.bind(this);
    }
    handleCancel(){
        this.props.history.push('/salarystructures');
    }
    handleValueTypeChange(e){
        let index = e.target.dataset.index;
        let heads = JSON.parse(JSON.stringify(this.props.config));
        heads[index].default_valuetype = e.target.value;
        this.props.modStructure(heads);
    }
    componentDidMount(){
        axios.get('/salarystructures/create')
        .then((response)=>{
            console.log(response);
            if(response.data.status == 'success'){
                this.props.setConfig(response.data.data);
            }
            else 
                this.setState({loaderror:response.data.message})
        })
        .catch(function (error) {
          console.log(error);
        });
    }
    render(){
        let c = this.props.config;
        return(
        <div className='container-fluid'>
            {Object.keys(c).map((key)=>{
                return(
                    <div className="input-group input-group-sm mb-1" key={key}>
                        <div className="input-group-prepend">
                            <span className="input-group-text" id={`basic-addon-${key}`} style={{width:'120px'}}>
                                {c[key].presentation}
                            </span>
                            <select value={c[key].default_valuetype} onChange={this.handleValueTypeChange} data-index={key}>
                                <option disabled={true} value={-1} key={-1}>Select One</option>
                                {c[key].valuetype.map((value, index)=>{
                                    return <option key={index} value={index}>{value}</option>
                                })}
                            </select>
                        </div>
                    </div>
                );
            })}
            <LoadError message={this.state.loaderror} cancel={this.handleCancel}/>
        </div>);
    }
}
const AddStructure = connect(mapStateToProps, mapDispatchToProps)(ConnectedAddStructure);
export default AddStructure;

function LoadError(props){
    function goBack(){
        props.cancel();
    }
    if(props.message === '')
        return null;
    else {
        var errstyle = {display:'block'};
        return(
            <span className="invalid-feedback" role="alert" style={errstyle}>
                <strong className="mr-2">▶{props.message}</strong>
                <button type='button' className='btn btn-outline-danger badge badge-pill' onClick={goBack}> back </button> 
            </span>
        )
    }
}