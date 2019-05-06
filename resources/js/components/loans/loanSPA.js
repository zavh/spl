import React, { Component } from 'react';
import Create from './Create';
import LoanList from './LoanList';
import axios from 'axios';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { connect } from "react-redux";
import LoanEdit from './LoanEdit';
import ScheduleEdit from './ScheduleEdit';
import { setActiveLoans } from "./redux/actions/index";
function mapStateToProps (state)
{
  return {
      activeloans: state.activeloans,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setActiveLoans: loans=> dispatch(setActiveLoans(loans)),
     };
}
class ConnectedLoanSPA extends Component {
    getLoans(){
        axios.get('/loans/active')            
            .then(
                (response)=>{
                    this.props.setActiveLoans(response.data);
                });
    }
    
    componentDidMount(){
        this.getLoans();
    }

    render() {
        return (
            <div className="container-fluid">
                <div className="row justify-content-between">
                    <Router>
                    <div className="col-md-8">
                        
                            <Switch>
                                <Route exact path="/loans" render={(props) =>  <LoanList {...props} loans={this.props.activeloans}/>} />
                                <Route path="/loans/edit/:id/:index" render={(props) =>  <LoanEdit {...props}/>}/>
                            </Switch>
                                  
                    </div>
                    <div className="col-md-4">
                        
                            <Switch>
                                <Route exact path="/loans" component={Create}/>
                                <Route path="/loans/edit/:id/:index" component={ScheduleEdit}/>
                            </Switch>
                        
                    </div>
                    </Router>                
                </div>
            </div>
        );
    }
}
const LoanSPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedLoanSPA);
export default LoanSPA;