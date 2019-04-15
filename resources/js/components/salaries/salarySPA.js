import React, { Component } from 'react';
import MainPanel from './mainPanel';
import TaxConfig from './TaxConfig';
import { connect } from "react-redux";
import { setMainPanel } from "./redux/actions/index";

function mapStateToProps (state)
{
  return { mainPanel: state.mainPanel };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel))
    };
}

class ConnectedSalarySPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            panel:'Main',
            taxcfg:{},
        };
        this.switchToTax = this.switchToTax.bind(this);
        this.switchToMain = this.switchToMain.bind(this);
    }
    
    componentDidMount(){
        if(this.props.mainPanel === undefined){

            this.props.setMainPanel('Main');
        }
            
    }

    switchToTax(tc){
        this.setState({
            panel:'TaxConfig',
            taxcfg:tc,
        });
    }

    switchToMain(){
        this.setState({
            panel:'Main',
        });
    }

    render() {
        if(this.props.mainPanel === 'Main')
            return (
                <div className="container-fluid">
                    <MainPanel panelChange={this.switchToTax}/>
                </div>
            );
        else if(this.state.panel === 'TaxConfig')
            return (
                <div className="container-fluid">
                    <TaxConfig 
                        employee_id={this.state.taxcfg.employee_id}
                        fromYear={this.state.taxcfg.fromYear}
                        toYear={this.state.taxcfg.toYear}
                        panelChange={this.switchToMain}
                        />
                </div> 
            );
        else return <div>Loading</div>

    }
}

const SalarySPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedSalarySPA);
export default SalarySPA;