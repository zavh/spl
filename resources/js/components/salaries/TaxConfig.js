import React, { Component } from 'react';
import axios from 'axios';
import { connect } from "react-redux";
import { setMainPanel} from "./redux/actions/index";
import { BrowserRouter as Router, Route, Switch} from "react-router-dom";
import StyledLi from './tccomponents/StyledLi';

function mapStateToProps (state)
{
  return { 
      tabheads: state.tabheads,
      timeline: state.timeline,
      targetEmployee: state.targetEmployee,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
    };
}


class ConnectedTaxConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            panel:'Pi Configuration',
            menuItems: ['Slab Configuration', 'Tax Exemption Configuration', 'Investment Configuration'],
            links:['/config/slabs', '/config/exemptions', '/config/investment'],
        }
        this.activeLinkChange = this.activeLinkChange.bind(this);
        this.backToMain = this.backToMain.bind(this);
    }
    activeLinkChange(al){
        this.setState({panel:al});
    }
    backToMain(){
        this.props.setMainPanel("Main");
    }
    render(){
        return(
            <div>
                <Router>
                <div className="row my-1 small">
                    <div className="col-md-4">
                        <ul className="list-group">
                            {this.state.menuItems.map((item,index) =>
                                <StyledLi 
                                    item={item}
                                    target={this.state.panel}
                                    key={index}
                                    linkto={this.state.links[index]}
                                    onClick={this.activeLinkChange}
                                    />
                            )}
                        </ul>
                    </div>
                    <div className="col-md-8">
                        <Switch>
                            <Route path="/salaries" component={test} exact/>
                        </Switch>
                    </div>
                </div>
                </Router>
                <a href='javascript:void(0)' onClick={this.backToMain}>Back</a>
            </div>
            
        );
    }
}
const TaxConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedTaxConfig);
export default TaxConfig;
function test(){
    return <div>Test</div>
}