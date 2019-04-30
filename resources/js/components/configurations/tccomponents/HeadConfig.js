import React, { Component } from 'react';
import { connect } from "react-redux";
import { modSalaryConfig} from "../redux/actions/index";

function mapStateToProps (state)
{
  return { 
    salaryheads: state.salaryheads,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        modSalaryConfig: head => dispatch(modSalaryConfig(head)),
    };
}
class ConnectedHeadConfig extends Component{
    constructor(props){
        super(props);
        this.onTextFieldChange = this.onTextFieldChange.bind(this);
        this.onTaxableChange = this.onTaxableChange.bind(this);
    }
    onTextFieldChange(e){
        let head = {
            key:e.target.dataset.key,
            confname:e.target.dataset.confname,
            newval:e.target.value
        }
        this.props.modSalaryConfig(head);
    }
    onTaxableChange(e){
        let head = {
            key:e.target.dataset.key,
            confname:e.target.dataset.confname,
            newval:e.target.checked
        }
        this.props.modSalaryConfig(head);
    }
    
    render(){
        let p = this.props.p;
        return(
            <div className="input-group input-group-sm mb-1">
                <div className="input-group-prepend">
                    <span className="input-group-text" id={`basic-addon-${this.props.u}`}>Head Name : </span>
                </div>
                <input 
                    type="text"
                    className="form-control"
                    placeholder="Head Name"
                    aria-label={p.presentation}
                    aria-describedby={`basic-addon-${this.props.u}`}
                    value={p.presentation}
                    onChange={this.onTextFieldChange}
                    data-key={this.props.u}
                    data-confname={'presentation'}
                    />
                <div className="input-group-append">
                    <span className="input-group-text">Taxable : </span>
                </div>
                <div className="input-group-append">
                    <div className="input-group-text">
                        <input 
                            type="checkbox"
                            aria-label="Checkbox for following text input"
                            value={p.taxable}
                            checked={p.taxable}
                            onChange={this.onTaxableChange}
                            data-key={this.props.u}
                            data-confname={'taxable'}
                            />
                    </div>
                </div>
                <div className="input-group-append">
                    <span className="input-group-text">Tax Exemption : </span>
                </div>
                <input 
                    type="text"
                    className="form-control"
                    placeholder="Tax Exemption"
                    aria-label={'tax-exemption'}
                    aria-describedby={`basic-addon-tax-exemption`}
                    value={p.tax_exemption}
                    onChange={this.onTextFieldChange}
                    data-key={this.props.u}
                    data-confname={'tax_exemption'}
                    />
                <div className="input-group-append">
                    <span className="input-group-text">Uploadable : </span>
                </div>
                <div className="input-group-append">
                    <div className="input-group-text">
                        <input 
                            type="checkbox"
                            aria-label="Checkbox for following text input"
                            value={p.uploadable}
                            checked={p.uploadable}
                            onChange={this.onTaxableChange}
                            data-key={this.props.u}
                            data-confname={'uploadable'}
                            />
                    </div>
                </div>
                <div className="input-group-append">
                    <span className="input-group-text">At Gross : </span>
                </div>
                <select className="custom-select" value={p.gcalc} onChange={this.onTextFieldChange}  data-key={this.props.u} data-confname={'gcalc'}>
                    <option value='addition'>Addition</option>
                    <option value='deduction'>Deduction</option>
                    <option value='none'>None</option>
                </select>
                <div className="input-group-append">
                    <span className="input-group-text">At Payout : </span>
                </div>
                <select className="custom-select" value={p.pcalc} onChange={this.onTextFieldChange}  data-key={this.props.u} data-confname={'pcalc'}>
                    <option value='addition'>Addition</option>
                    <option value='deduction'>Deduction</option>
                    <option value='none'>None</option>
                </select>
            </div>
        )
    }
}

const HeadConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedHeadConfig);
export default HeadConfig;