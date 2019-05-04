import React, { Component } from 'react';
import Card from '../commons/Card';
import Accordion from '../commons/Accordion';

export default class LoanList extends Component {
    constructor(props){
        super(props);
        this.state = {
            targetid:0,
            targetindex:-1,
        };

        this.handleEdit = this.handleEdit.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        this.editStatus = this.editStatus.bind(this);
    }

    handleEdit(id, index){
        this.props.history.push('/loans/edit/'+id+'/'+index);
    }

    handleCancel(){
        this.setState({leftpane:'viewList'});
    }

    editStatus(loan){
        this.props.bridge(loan, this.state.targetindex);
    }

    render() {
        return(
            <Card title='Currently Running Loans'>
                <Accordion accid='loansAccordion' data={this.props.loans} edit={this.handleEdit}/>
            </Card>
        );
    }
}