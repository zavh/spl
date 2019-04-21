import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Switch} from "react-router-dom";
import StyledLi from './StyledLi';
import SlabConfig from './SlabConfig';

export default class TaxConfig extends Component{
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
                <div className="row my-1 small">
                    <div className="col-md-4">
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
                    <div className="col-md-8">
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
