import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Switch} from "react-router-dom";
import { connect } from "react-redux";
import StyledLi from './StyledLi';
import SlabConfig from './SlabConfig';
function mapStateToProps (state)
{
  return { 

    };
}

function mapDispatchToProps(dispatch) {
    return {

    };
}
class ConnectedTaxConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            panel:'Slab Configuration',
            menuItems: ['Slab Configuration', 'Tax Exemption Configuration', 'Investment Configuration'],
            links:['/configurations/taxconfig/slabs', '/configurations/taxconfig/exemptions', '/configurations/taxconfig/investment'],
        }
        this.activeLinkChange = this.activeLinkChange.bind(this);
    }
    activeLinkChange(al){
        this.setState({panel:al});
    }

    render(){
        return(
            <Router>
                <div className="row m-4 small">
                    <div className="col-md-3">
                        <ul className="list-group shadow-sm">
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
                    <div className="col-md-9">
                        <Switch>
                            <Route path="/configurations" component={SlabConfig} exact/>
                            <Route path="/configurations/taxconfig/slabs" component={SlabConfig}/>
                        </Switch>
                    </div>
                </div>
            </Router>
        );
    }
}
const TaxConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedTaxConfig);
export default TaxConfig;
