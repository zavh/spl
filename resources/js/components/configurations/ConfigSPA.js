import React, { Component } from 'react';
import { connect } from "react-redux";
import { BrowserRouter as Router, Route, Switch, Link} from "react-router-dom";
import TaxConfig from './tccomponents/TaxConfig';
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
    
        this.toggle = this.toggle.bind(this);
        this.state = {
          dropdownOpen: false
        };
      }
    
      toggle() {
        this.setState({
          dropdownOpen: !this.state.dropdownOpen
        });
      }
    
      render() {
        return (
            <Router>
            <ul className="nav nav-tabs">
                <li className="nav-item">
                    <Link to='/configurations/taxconfig' className="nav-link active"> Tax Configuration </Link>
                </li>
                <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                    <div className="dropdown-menu">
                    <a className="dropdown-item" href="#">Action</a>
                    <a className="dropdown-item" href="#">Another action</a>
                    <a className="dropdown-item" href="#">Something else here</a>
                    <div className="dropdown-divider"></div>
                    <a className="dropdown-item" href="#">Separated link</a>
                    </div>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="#">Link</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link disabled" href="#" tabIndex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <div className='container-fluid'>
                <Switch>
                    <Route path="/configurations" component={TaxConfig} exact/>
                    <Route path="/configurations/taxconfig" component={TaxConfig} exact/>
                </Switch>
            </div>
            </Router>
        );
      }
}
const ConfigSPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedConfigSPA);
export default ConfigSPA;