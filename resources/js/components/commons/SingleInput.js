import React, { Component } from 'react';

export default class SingleInput extends Component {
    constructor(props){
        super(props);
        this.state = {
            value:'',
        }
        this.inputChange = this.inputChange.bind(this);
        this.errorProcess = this.errorProcess.bind(this);
        this.onInsert = this.onInsert.bind(this);
    }

    inputChange(e){
        this.props.onChange(e.target.value);
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
    onInsert(){
        this.props.onInsert();
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
                    <input type={this.props.type} className="form-control" name={this.props.name} onChange={this.inputChange} value={this.props.value}/>
                    <div className="input-group-append">
                        <button className="btn btn-outline-secondary" type="button" id="button-addon2" onClick={this.onInsert}>Add</button>
                    </div>
                    {this.errorProcess()}
                </div>
            </div>
        );
    }
}