import React, { Component } from 'react';
import Card from '../commons/Card';
import Accordion from '../commons/Accordion';
import LoanEdit from './edit';
import Create from './create';
export default class LoanSPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            newloan:[],
            leftpane:'viewList',
            activeloans:[],
            activeedit:{},
        };

        this.newLoanArrived = this.newLoanArrived.bind(this);
        this.leftPaneSwitch = this.leftPaneSwitch.bind(this);
        this.handleEditLoan = this.handleEditLoan.bind(this);
        this.editNameChange = this.editNameChange.bind(this);
    }

    newLoanArrived(loan){
        this.setState(prevState => ({
            activeloans: [...prevState.activeloans, loan]
            })) 
    }

    handleEditLoan(id, index){
        let al = this.state.activeloans[index];

        al['index'] = index;
        let activeedit = Object.assign({}, al);
        this.setState({activeedit});
        this.setState({
            leftpane:'editLoan',
        })
        this.setState({leftpane:'editLoan'});
    }

    editNameChange(value){
        console.log(value);
    }

    getLoans(){
        axios.get('/loans/active')
            .then((response)=>this.setState({
                activeloans:[...response.data]
            })
        );
    }
    componentDidMount(){
        this.getLoans();
    }

    leftPaneSwitch(){
        if(this.state.leftpane == 'viewList'){
            return(
                <Card title='Currently Running Loans'>
                    <Accordion accid='loansAccordion' data={this.state.activeloans} edit={this.handleEditLoan}/>
                </Card>
            );
        }
        else if(this.state.leftpane == 'editLoan'){
            return(
                <Card title='Edit Loan'>
                    <LoanEdit 
                        amtChange={this.editNameChange}
                        loan={this.state.activeedit}
                    />
                </Card>
            );
        }
    }
    render() {
        return (

            <div className="container">
                <div className="row justify-content-between">
                        {this.leftPaneSwitch()}
                        <Create bridge={this.newLoanArrived}/>
                </div>
            </div>
        );
    }
}