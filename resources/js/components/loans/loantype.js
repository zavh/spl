import React, { Component } from 'react';

export default class LoanType extends Component {
    constructor(props){
        super(props);
        this.inputChange = this.inputChange.bind(this);
        this.prepareSelect = this.prepareSelect.bind(this);
        this.errorProcess   = this.errorProcess.bind(this);
    }

    inputChange(e){
        this.props.onChange(e.target.value);
    }

    prepareSelect(){
        return (
            <select value={this.props.selected} name={this.props.name} className='form-control' onChange={this.inputChange}>
                <option key='0' value='0' disabled>Select One</option>
                <option key='1' value='General Purpose Loan'>General Purpose Loan</option>
                <option key='2' value='Hire Purchase'>Hire Purchase</option>
            </select>
        );
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
                        <span className="input-group-text" id="inputGroup-sizing-sm" style={labelSize}>
                            Loan Type
                        </span>
                    </div>
                    {this.prepareSelect()}
                    {this.errorProcess()}
                </div>
            </div>
        );
    }
}