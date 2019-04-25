import React, { Component } from 'react';
import { connect } from "react-redux";
import { BrowserRouter as Router, Route, Switch, Link} from "react-router-dom";
import TaxConfig from './tccomponents/TaxConfig';
import SalaryHeads from './tccomponents/SalaryHeads';
function mapStateToProps (state)
{
  return { 

    };
}

function mapDispatchToProps(dispatch) {
    return {

    };
}

class ConnectedConfigSPA extends Component{
    constructor(props) {
        super(props);
        this.state = {
            panel:'Salary Heads',
            menuItems: ['Salary Heads', 'Tax Configuration'],
            links:['/configurations/salaryheads', '/configurations/taxconfig'],
        }
        this.activeLinkChange = this.activeLinkChange.bind(this);        
    }

    activeLinkChange(al){
        this.setState({panel:al});
    }
    render() {
        return (
            <Router>
            <ul className="nav nav-tabs">
                {this.state.menuItems.map((item, index)=>{
                    return(
                        <StyledNav 
                            item={item}
                            target={this.state.panel}
                            key={index}
                            linkto={this.state.links[index]}
                            onClick={this.activeLinkChange}
                        />
                    )
                })}
            </ul>
            <div className='container-fluid'>
                <Switch>
                    <Route path="/configurations" component={SalaryHeads} exact/>
                    <Route path="/configurations/taxconfig" component={TaxConfig} exact/>
                    <Route path="/configurations/salaryheads" component={SalaryHeads} exact/>
                </Switch>
            </div>
            </Router>
        );
    }
}
const ConfigSPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedConfigSPA);
export default ConfigSPA;

function StyledNav(props){
    function onClick(){
        props.onClick(props.item)
    }
    if(props.item === props.target)
        return(
            <li className="nav-item">
                <Link to={props.linkto} className="nav-link active" onClick={onClick}> {props.item} </Link>
            </li>
        );
    else 
        return(
            <li className="nav-item">
                <Link to={props.linkto} className="nav-link" onClick={onClick}> {props.item} </Link>
            </li>
        );
}
