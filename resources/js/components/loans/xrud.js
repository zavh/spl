import React, { Component } from 'react';
import Card from '../commons/Card';
import Accordion from '../commons/Accordion';
import LoanEdit from './edit';

export default class Modify extends Component {
    constructor(props){
        super(props);
        this.state = {
            leftpane:'viewList',
            targetid:0,
            targetindex:-1,
        };

        this.handleEdit = this.handleEdit.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        this.editStatus = this.editStatus.bind(this);
    }

    handleEdit(id, index){
        this.setState(
            {
                leftpane:'editLoan',
                targetid:id,
                targetindex:index,
            }
        );
    }

    handleCancel(){
        this.setState({leftpane:'viewList'});
    }

    editStatus(loan){
        this.setState({leftpane:'viewList'});
        this.props.bridge(loan, this.state.targetindex);
    }

    render() {
        if(this.state.leftpane == 'viewList'){
            return(
                <Card title='Currently Running Loans'>
                    <Accordion accid='loansAccordion' data={this.props.loans} edit={this.handleEdit}/>
                </Card>
            );
        }
        else if(this.state.leftpane == 'editLoan'){
            return(
                <Card title='Edit Loan'>
                    <LoanEdit
                        id={this.state.targetid}
                        cancel={this.handleCancel}
                        bridge={this.editStatus}
                    />
                </Card>
            );
        }
    }
}