import React, { Component } from 'react';

export default class Input extends Component {
    constructor(props){
        super(props);
        this.state = {
            value:'',
            required:true
        }
        this.inputChange = this.inputChange.bind(this);
        this.errorProcess = this.errorProcess.bind(this);
    }

    inputChange(e){
        this.props.onChange(e.target.name, e.target.value);
    }

    componentDidMount(){
        
        if(this.props.required !== undefined){
            if(this.props.required == false){
                this.setState({required:false});
            }
        }
    }

    errorProcess(){
        if(this.props.errors.length > 0){
            const divStyle = {
                display:'block',
              };
            return (
                <span className="invalid-feedback" role="alert" style={divStyle}>
                    {this.props.errors.map((e,i)=><strong key={i} className='mr-2'>▶{e}</strong>)}
                </span>
            )
        }
    }
    render() {
        const labelSize = {
            width: this.props.labelSize,
          };
        return (
            <div className="form-group row my-1">
                <div className="input-group input-group-sm col-md-12">
                    <div className="input-group-prepend">
                        <span className="input-group-text" style={labelSize}>{this.props.label}</span>
                    </div>
                    <input 
                        type={this.props.type}
                        className="form-control"
                        name={this.props.name}
                        onChange={this.inputChange}
                        value={this.props.value}
                        required={this.state.required} />
                    
                    {this.errorProcess()}
                </div>
            </div>
        );
    }
}