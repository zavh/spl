import React, { Component } from 'react';
import Create from './create';
import Modify from './xrud';
import axios from 'axios';
export default class LoanSPA extends Component {
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
                (response)=>this.setState({
                activeloans:[...response.data]
            })
        );
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
                        <Modify bridge={this.loanModified} loans={this.state.activeloans}/>
                        <Create bridge={this.loanArrived}/>
                </div>
            </div>
        );
    }
}