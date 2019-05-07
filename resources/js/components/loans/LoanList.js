import React, { Component } from 'react';
import Card from '../commons/Card';

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

    handleEdit(e){
        let id = e.target.dataset.id;
        let index = e.target.dataset.index;
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
            <Card title='Currently Active Loans'>
                <div className='m-2'>
                <table className='table text-center table-hover table-striped table-bordered mb-0'>
                    <tbody className='small'>
                        <tr><th>Employee Name</th><th>Employee ID</th><th>Loan ID</th><th>Tenure</th><th>Amount</th><th>Start</th><th>End</th><th>Action</th></tr>
                        {this.props.loans.map((loan, index)=>{
                            return(
                                <tr className='m-0 p-0' key={index}>
                                    <td className='m-0 p-0'>{loan.name}</td>
                                    <td className='m-0 p-0'>{loan.employee_id}</td>
                                    <td className='m-0 p-0'>{loan.id}</td>
                                    <td className='m-0 p-0'>{loan.tenure}</td>
                                    <td className='m-0 p-0'>{loan.amount}</td>
                                    <td className='m-0 p-0'>{loan.start_date}</td>
                                    <td className='m-0 p-0'>{loan.end_date}</td>
                                    <td className='m-0 p-0'>
                                        <a
                                            href="javascript:void(0)"
                                            onClick={this.handleEdit}
                                            data-id={loan.id}
                                            data-index={index}
                                            className='text-dark'
                                            >
                                           Details</a>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
                </div>
            </Card>
        );
    }
}