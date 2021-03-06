import React, { Component } from 'react';
import MainPanel from './mainPanel';
import IndvTaxCalc from './IndvTaxCalc';
import { connect } from "react-redux";
import { setMainPanel, setPayYear, setTabHeads, setSalaryRows, setRefTimeline, setIndexing, setBankAccounts} from "./redux/actions/index";
import axios from 'axios';

function mapStateToProps (state)
{
  return { 
      mainPanel: state.mainPanel,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setMainPanel: panel=> dispatch(setMainPanel(panel)),
        setPayYear: timeline=> dispatch(setPayYear(timeline)),
        setRefTimeline: timeline=> dispatch(setRefTimeline(timeline)),
        setTabHeads: tabheads=> dispatch(setTabHeads(tabheads)),
        setSalaryRows: salaryrows=> dispatch(setSalaryRows(salaryrows)),
        setIndexing: indexing=>dispatch(setIndexing(indexing)),
        setBankAccounts: bankaccounts=>dispatch(setBankAccounts(bankaccounts)),
    };
}

class ConnectedSalarySPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            status:'',
            message:'',
        };
    }
    
    componentDidMount(){
        if(this.props.mainPanel === undefined){
            this.props.setMainPanel('Main');
        }

        axios.get('/salaries/dbcheck')
        .then(
            (response)=>{
                if(response.data.status == 'success'){
                    console.log(response);
                    const timeline = {
                        fromYear: response.data.fromYear,
                        toYear: response.data.toYear,
                        month : response.data.month,
                    }
                    this.props.setPayYear(timeline);
                    this.props.setRefTimeline(timeline);
                    this.props.setTabHeads(response.data.tabheads);
                    this.props.setSalaryRows(response.data.data);
                    this.props.setIndexing(response.data.indexing);
                    this.props.setBankAccounts(response.data.bankaccounts);

                    this.setState({status:'success'});
                }
                else {
                    this.setState({status:'failed',message:response.data.message});
                }
            }
        )
        .catch(function (error) {
            console.log(error);
          });
            
    }

    render() {
        if(this.props.mainPanel === 'Main'){
            if(this.state.status == 'success')
            return (
                <div className="container-fluid">
                    <MainPanel status={this.state.status}/>
                </div>
            );
            else 
                return (
                    <div className='container-fluid'>
                        <div className="p-3 mb-2 bg-warning text-dark">
                            {this.state.message}
                        </div>
                        
                    </div>
                )
        }

        else if(this.props.mainPanel === 'IndvTaxCalc')
            return (
                <div className="container-fluid">
                    <IndvTaxCalc />
                </div> 
            );
        else return <div>Loading</div>

    }
}

const SalarySPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedSalarySPA);
export default SalarySPA;