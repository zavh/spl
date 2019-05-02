import React, { Component } from 'react';
import Create from './Create';
import LoanList from './LoanList';
import axios from 'axios';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { connect } from "react-redux";
import LoanEdit from './LoanEdit';
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
    constructor(props){
        super(props);
        this.state = {
            activeloans:[],
        };

        this.loanArrived = this.loanArrived.bind(this);
        this.loanModified = this.loanModified.bind(this);
    }
    getLoans(){
        axios.get('/loans/active')            
            .then(
                (response)=>{
                    this.setState({activeloans:[...response.data]})
                    this.props.setActiveLoans(response.data);
                });
    }
    
    componentDidMount(){
        this.getLoans();
    }

    loanArrived(loan){
        this.setState(prevState => ({
            activeloans: [...prevState.activeloans, loan]
            }));
    }

    loanModified(loan, index){
        let activeloans = [...this.state.activeloans];
        activeloans[index]['params'] = Object.assign({},loan);
        this.setState(activeloans);
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-between">
                    <Router>
                    <div className="col-md-6 mb-2">
                        
                            <Switch>
                                <Route exact path="/loans" render={(props) =>  <LoanList {...props} loans={this.state.activeloans}/>} />
                                <Route path="/loans/edit/:id/:index" render={(props) =>  <LoanEdit {...props}/>}/>
                            </Switch>
                                  
                    </div>
                    <div className="col-md-6 mb-2">
                        
                            <Switch>
                                <Route exact path="/loans" component={Create}/>
                                <Route path="/loans/edit/:id/:index" component={Test}/>
                            </Switch>
                        
                    </div>
                    </Router>                
                </div>

                        {/* <Modify bridge={this.loanModified} loans={this.state.activeloans}/> */}
                        {/* <Create bridge={this.loanArrived}/> */}
            </div>
        );
    }
}
const LoanSPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedLoanSPA);
export default LoanSPA;

function Test(){
    return <div>Test</div>
}