import React, { Component } from 'react';

export default class Submit extends Component {
    constructor(props){
        super(props);

        this.handleCancel = this.handleCancel.bind(this);
    }

    handleCancel(e){
        this.props.onCancel(e);
    }
    render() {
        return (
            <div className="row">
                <div className="col-6 m-0 pr-1"> 
                    <input type="submit" className="btn btn-outline-primary btn-sm btn-block" value={this.props.submitLabel}/>
                </div>
                <div className="col-6 pl-1"> 
                    <button className="btn btn-outline-dark btn-sm btn-block" onClick={this.handleCancel}>{this.props.cancelLabel}</button>
                </div>
            </div>
        );
    }
}