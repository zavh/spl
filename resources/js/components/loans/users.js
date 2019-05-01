import React, { Component } from 'react';

export default class Users extends Component {
    constructor(props){
        super(props);
        this.inputChange    = this.inputChange.bind(this);
        this.prepareSelect  = this.prepareSelect.bind(this);
        this.errorProcess   = this.errorProcess.bind(this);
    }

    inputChange(e){
        this.props.onChange(e.target.value);
    }

    prepareSelect(){
        if(this.props.department > 0){
            if(this.props.users.length > 0)
                return (
                    <select value={this.props.selected} name={this.props.name} className='form-control' onChange={this.inputChange}>
                        <option key='0' value='0' disabled>Select One</option>
                        {this.props.users.map((user, index) => (
                            <option key={index} value={user.sid}>
                                {user.username} - {user.name}
                            </option>
                        ))}
                    </select>
                );
            else 
            return (<div className='form-control text-danger'>Department does not have any user defined yet.</div>);
        }
        return (<div className='form-control'>Please select a department to access Employee</div>);
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
                            Employee
                        </span>
                    </div>
                    {this.prepareSelect()}
                    {this.errorProcess()}
                </div>
            </div>
        );
    }
}