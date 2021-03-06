import React, { Component } from 'react';
import AddStrcture from './AddStructure';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { connect } from "react-redux";
import { setStructures, } from "./redux/actions/index";

function mapStateToProps (state)
{
  return {
    salaryStructures : state.salaryStructures,
    currentStructure : 0,
     };
}

function mapDispatchToProps(dispatch) {
    return {
        setStructures: panel=> dispatch(setStructures(panel)),
     };
}
class ConnectedSStructureSPA extends Component{
    constructor(props){
        super(props);
        this.state = {
            targetid:0,
        }
    }
    componentDidMount(){
        axios.get('/salarystructures/getall')
        .then((response)=>{
            console.log(response);
            this.props.setStructures(response.data);
        })
        .catch(function (error) {
          console.log(error);
        });
    }
    render(){
        return(
            <Router>
                <Switch>
                    <Route exact path="/salarystructures" render={(props) =>  <ShowAllStructures {...props} s={this.props.salaryStructures}/>} />
                    <Route exact path="/salarystructures/add" component={AddStrcture} />
                </Switch>
            </Router>
        );
    }
}
const SStructureSPA = connect(mapStateToProps, mapDispatchToProps)(ConnectedSStructureSPA);
export default SStructureSPA;

function ShowAllStructures(props){
    return(
        <div className="container-fluid">
            <div className="row" >
                <div className="col-md-6 col-lg-6">
                    <div className="card mb-4 shadow-sm h-md-250">
                        <div className=" mb-0 bg-white rounded">
                            <div className="media text-muted">
                                <div className="media-body small">
                                    <div className="d-flex justify-content-between align-items-center w-100">
                                        <strong className="text-dark pl-1 pt-1">List of Salary Structures</strong>
                                        <span>
                                        <Link to="/salarystructures/add" className='small mx-2'>Add Structure</Link>
                                        </span>
                                    </div>
                                    {props.s.map((structure, index)=>{
                                        return (
                                            <div className="d-flex justify-content-between border-top" key={index}>
                                            <div className="pl-2"> 
                                                Structure Name: <strong className="text-primary">{structure.structurename}</strong> 
                                            </div>
                                            {/* <span className="mx-2">
                                                <a href="javascript:void(0)" onclick="showStructure('{{$structure->id}}')" className='badge-success badge padge-pill'>Show</a>
                                                @if(count($structure->users)>0)
                                                <a href="javascript:void(0)" onclick="alert('Structure assigned to users, cannot delete')" className='badge-secondary badge padge-pill'>Delete</a>
                                                @else
                                                <a href="javascript:void(0)" onclick="deleteSalaryStructure('{{$structure->structurename}}','{{$structure->id}}')" className='badge-danger badge padge-pill'>Delete</a>
                                                @endif
                                                <a href="/salarystructures/{{$structure->id}}/edit" className="badge-success badge padge-pill">Edit</a>
                                            </span> */}
                                        </div>
                                        )
                                    })}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 col-lg-6" id="salary_structure_details">

                </div>
            </div>
        </div>
    );
}

function StructureDetails(props){
    console.log(props);
    return(<div>Routing through Child</div>);
}