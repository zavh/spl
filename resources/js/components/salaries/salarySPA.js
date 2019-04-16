import React, { Component } from 'react';
import MainPanel from './mainPanel';
import TaxConfig from './TaxConfig';
import { connect } from "react-redux";
import { setMainPanel, setPayYear, setTabHeads, setSalaryRows} from "./redux/actions/index";
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
        setTabHeads: tabheads=> dispatch(setTabHeads(tabheads)),
        setSalaryRows: salaryrows=> dispatch(setSalaryRows(salaryrows)),
    };
}

class ConnectedSalarySPA extends Component {
    constructor(props){
        super(props);
        this.state = {
            taxcfg:{},
            status:''
        };
    }
    
    componentDidMount(){
        if(this.props.mainPanel === undefined){
            this.props.setMainPanel('Main');
        }

        axios.get('/salaries/dbcheck')
        .then(
            (response)=>{
                console.log(response);
                const timeline = {
                    fromYear: response.data.fromYear,
                    toYear: response.data.toYear,
                    month : response.data.month,
                }
                this.props.setPayYear(timeline);
                this.props.setTabHeads(response.data.tabheads);
                this.props.setSalaryRows(response.data.data);

                this.setState({status:'success'});
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
                return <div>Loading</div>
        }

        else if(this.props.mainPanel === 'TaxConfig')
            return (
                <div className="container-fluid">
                    <TaxConfig />
                </div> 
            );
        else return <div>Loading</div>

    }
}

const SalarySPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedSalarySPA);
export default SalarySPA;