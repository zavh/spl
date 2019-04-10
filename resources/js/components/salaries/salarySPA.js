import React, { Component } from 'react';
import MainPanel from './mainPanel';
export default class SalarySPA extends Component {
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
            <div className="container-fluid">
                <MainPanel />
            </div>
        );
    }
}