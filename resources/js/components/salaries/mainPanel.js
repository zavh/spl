import React, { Component } from 'react';
import axios from 'axios';
import FileUpload from '../commons/FileUpload';
export default class MainPanel extends Component {
    constructor(props){
        super(props);
        this.state = {
            tabheads:{},
            salaryrow:[],
            status:'',
            message:'',
        }
    }
    componentDidMount(){
        axios.get('/salaries/dbcheck')
        .then(
            (response)=>{
                console.log(response);
                status = response.data.status;
                this.setState({status:status})
                if(status === 'success'){
                    this.setState({
                        tabheads : response.data.tabheads,
                        salaryrow : response.data.data, 
                    });
                }
                else if(status === 'fail'){
                    this.setState({message:response.data.message});
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
        ;
    }
    render(){
        if(this.state.status === 'success'){
            return(
                <div>
                    <FileUpload />
                    <table className='table table-sm table-bordered table-striped small'>
                        <tbody>
                            <tr>
                            { Object.keys(this.state.tabheads).map((key, index)=>{
                                return <th key={index}>{this.state.tabheads[key]}</th>
                            })}
                            </tr>
                            {this.state.salaryrow.map((e,i)=>{
                                return <tr key={i}>
                                    {Object.keys(e).map((key,index)=>{
                                        return <td key={index}>
                                            {e[key]}
                                        </td>
                                    })}
                                </tr>
                            })}
                        </tbody>
                    </table>
                </div>
            );
        }
        else if(this.state.status === 'fail'){
            return(
                <div>{this.state.message}</div>
            );
        }
        else return(
            <div>Loading Data</div>
        );
    }
}