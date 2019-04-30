import React, { Component } from 'react';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { connect } from "react-redux";
import { setConfig, setCurrent, addStructure, setConfigLoaded} from "./redux/actions/index";
import SingleInput from '../commons/SingleInput';
import Submit from '../commons/submit';
function mapStateToProps (state)
{
  return {
      config: state.config,
      configloaded: state.configloaded,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setConfig: structure => dispatch(setConfig(structure)),
        setCurrent: index => dispatch(setCurrent(index)),
        addStructure: structure => dispatch(addStructure(structure)),
        setConfigLoaded: flag => dispatch(setConfigLoaded(flag)),
     };
}
class ConnectedAddStructure extends Component{
    constructor(props){
        super(props);
        this.state = {
            loaderror: '',
            config:{},
            name:'',
            nameerror:[],
        }
        this.handleCancel = this.handleCancel.bind(this);
        this.getValueProp = this.getValueProp.bind(this);
        this.handleValueChange = this.handleValueChange.bind(this);
        this.ssNameChange = this.ssNameChange.bind(this);
        this.onInsertSSName = this.onInsertSSName.bind(this);
        this.addStructure = this.addStructure.bind(this);
        
    }
    ssNameChange(value){
        this.setState({name:value});
    }
    onInsertSSName(){
        console.log(this.state);
    }
    handleCancel(){
        this.props.history.push('/salarystructures');
    }

    handleValueChange(e){
        let index = e.target.dataset.index;
        let heads = JSON.parse(JSON.stringify(this.state.config));
        heads[index][e.target.name] = e.target.value;
        this.setState({config:heads});
    }
    addStructure(e){
        e.preventDefault();
        const commons = ['gcalc', 'pcalc', 'default_valuetype', 'presentation',];
        const savethese = [
            ['profile_field'],
            ['percentage', 'threshold'],
            ['fixed_value'],
            [],
            ['fnname']
        ];
        let config = this.state.config;
        // console.log(config);
        let structure = {}, dvt = -1;
        for(var key in config){
            dvt = config[key].default_valuetype;
            var combined_fields = commons.concat(savethese[dvt]);
            structure[key] = {};
            for(var i=0;i<combined_fields.length;i++){
                structure[key][combined_fields[i]] = config[key][combined_fields[i]];
            }
        }
        axios.post('/salarystructures',{'structurename':this.state.name, structure:JSON.stringify(structure)})
        .then((response)=>{
            console.log(response);
            // if(response.data.status == 'success'){
            //     this.props.setConfig(response.data.data);
            //     this.props.setConfigLoaded(true);
            //     this.setState({config:response.data.data})
            // }
            // else 
            //     this.setState({loaderror:response.data.message})
        })
        .catch(function (error) {
          console.log(error);
        });
        console.log(structure);
    }
    getValueProp(key){
        let dv = this.state.config[key].default_valuetype;
        if(dv <0)
            return null;
        else {
            let s = this.state.config[key];
            if(dv ==0){
                return(
                    <React.Fragment>
                        <div className="input-group-append">
                            <span className="input-group-text">
                                Profile Field
                            </span>
                        </div>
                        <input type='text' value={s.profile_field} onChange={this.handleValueChange} data-index={key} name='profile_field' required/>
                    </React.Fragment>
                )
            }
            else if(dv == 1)
                return(
                    <React.Fragment>
                        <div className="input-group-append">
                            <span className="input-group-text">
                                Percentage
                            </span>
                        </div>
                        <input type='text' value={s.percentage} onChange={this.handleValueChange} data-index={key} name='percentage' required/>
                        <div className="input-group-append">
                            <span className="input-group-text">
                                %
                            </span>
                        </div>
                        <div className="input-group-append">
                            <span className="input-group-text">
                                Max Value
                            </span>
                        </div>
                        <input type='number' value={s.threshold} onChange={this.handleValueChange} data-index={key} style={{MozAppearance:'textfield'}} min={0} name='threshold' required/>
                    </React.Fragment>
                );
            else if (dv == 2)
                return(
                    <React.Fragment>
                        <div className="input-group-append">
                            <span className="input-group-text" style={{width:'120px'}}>
                                Fixed Value
                            </span>
                        </div>
                        <input type='text' value={s.fixed_value} onChange={this.handleValueChange} data-index={key} name='fixed_value' required/>
                    </React.Fragment>
                )
            else if (dv == 4)
            return(
                <React.Fragment>
                    <div className="input-group-append">
                        <span className="input-group-text" style={{width:'120px'}}>
                            Function Name
                        </span>
                    </div>
                    <input type='text' value={s.fnname} onChange={this.handleValueChange} data-index={key} name='fnname' required/>
                </React.Fragment>
            )
        }
            
    }

    componentDidMount(){
        if(!this.props.configloaded){
            axios.get('/salarystructures/create')
            .then((response)=>{
                console.log(response);
                if(response.data.status == 'success'){
                    this.props.setConfig(response.data.data);
                    this.props.setConfigLoaded(true);
                    this.setState({config:response.data.data})
                }
                else 
                    this.setState({loaderror:response.data.message})
            })
            .catch(function (error) {
              console.log(error);
            });
        }
        else 
        this.setState({config:this.props.config})
    }
    render(){
        let c = this.state.config;
        if(this.state.loaderror === '')
            return(
                <div className='container'>
                    <form onSubmit={this.addStructure}>
                        
                        <SingleInput onChange={this.ssNameChange} onInsert={this.onInsertSSName} errors={this.state.nameerror} label='Salary Structure Name:'/>
                        {Object.keys(c).map((key)=>{
                            return(
                                <div className="input-group input-group-sm mb-1 small" key={key}>
                                    <div className="input-group-prepend">
                                        <span className="input-group-text" id={`basic-addon-${key}`} style={{width:'120px'}}>
                                            {c[key].presentation}
                                        </span>
                                    </div>
                                    <select value={c[key].default_valuetype} onChange={this.handleValueChange} data-index={key} name='default_valuetype' required>
                                        <option disabled={true} value={-1} key={-1}>Select One</option>
                                        {c[key].valuetype.map((value, index)=>{
                                            return <option key={index} value={index}>{value}</option>
                                        })}
                                    </select>
                                    {this.getValueProp(key)}
                                </div>
                            );
                        })}
                        <Submit submitLabel='Add Structure' cancelLabel='Cancel' onCancel={this.handleCancel}/>
                    </form>
                </div>);
            else return (<LoadError message={this.state.loaderror} cancel={this.handleCancel}/>);
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