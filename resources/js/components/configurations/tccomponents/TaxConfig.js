import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Switch} from "react-router-dom";
import { connect } from "react-redux";
import StyledLi from './StyledLi';
import SlabConfig from './SlabConfig';
import {SaveButton} from './SaveButton';
import { setSlab, setCategories, setFSData, setSlabDBStatus, setSlabInitiation, setSlabSavingsFlag } from "../redux/actions/index";
function mapStateToProps (state)
{
  return {
    slabs:state.slabs,
    fsdata:state.fsdata,
    firstSlabCategories:state.firstSlabCategories,
    slabdbstatus: state.slabdbstatus,
    slabinitiated: state.slabinitiated,
    slabneedsaving: state.slabneedsaving,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        setSlab: slabs => dispatch(setSlab(slabs)),
        setCategories: catagories => dispatch(setCategories(catagories)),
        setFSData: fsdata => dispatch(setFSData(fsdata)),
        setSlabDBStatus: slabdbstatus => dispatch(setSlabDBStatus(slabdbstatus)),
        setSlabInitiation: init => dispatch(setSlabInitiation(init)),
        setSlabSavingsFlag: init => dispatch(setSlabSavingsFlag(init)),
    };
}
class ConnectedTaxConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            panel:'Slab Configuration',
            menuItems: ['Slab Configuration', 'Investment Configuration'],
            links:['/slabs', '/investment'],
        }
        this.activeLinkChange = this.activeLinkChange.bind(this);
        this.saveConfig = this.saveConfig.bind(this);
    }
    activeLinkChange(al){
        this.setState({panel:al});
    }

    saveConfig(){
        var formData = new FormData();
        let data = {};
        data['slabs'] = this.props.slabs;
        data['fsdata'] = this.props.fsdata;
        data['categories'] = this.props.firstSlabCategories;
        formData.set('data', JSON.stringify(data));
        formData.set('field','taxconfig');
        if(this.props.slabdbstatus){
            axios.post('/configurations/taxconfig', {
                data:JSON.stringify(data),
                _method:'patch'})
            .then((response)=>{
              status = response.data.status;
              if(status == 'failed'){
                  console.log(response)
              }
              else if(status == 'success'){
                  console.log(response);
                  this.props.setSlabDBStatus(true);
              }
            })
            .catch(function (error) {
              console.log(error);
            });
        }
        else {
            axios.post('/configurations', formData)
            .then((response)=>{
              status = response.data.status;
              if(status == 'failed'){
                  console.log(response)
              }
              else if(status == 'success'){
                  console.log(response);
                  this.props.setSlabDBStatus(true);
              }
            })
            .catch(function (error) {
              console.log(error);
            });
        }
        this.props.setSlabSavingsFlag(false);
    }
    componentDidMount(){
        if(!this.props.slabdbstatus){
            axios.get('/configurations/taxconfig/edit')
            .then((response)=>{
                if(response.data.status == 'success'){
                    console.log(response);
                  this.props.setSlab(response.data.data.slabs);
                  this.props.setCategories(response.data.data.categories);
                  this.props.setFSData(response.data.data.fsdata);
                  this.props.setSlabDBStatus(true);
              }
              this.props.setSlabInitiation(true);
            })
            .catch(function (error) {
              console.log(error);
            });
        }
    }
    componentDidUpdate(prev){
        if( this.props.slabinitiated && this.props.slabs != prev.slabs)
            this.props.setSlabSavingsFlag(true);
    }
    render(){
        let url = this.props.match.url;
        return(
            <Router>
                <div className="row m-4 small">
                    <div className="col-md-3">
                        <ul className="list-group shadow-sm">
                            {this.state.menuItems.map((item,index) =>
                                <StyledLi 
                                    item={item}
                                    target={this.state.panel}
                                    key={index}
                                    linkto={this.state.links[index]}
                                    onClick={this.activeLinkChange}
                                    url={this.props.match.url} 
                                    />
                            )}
                        </ul>

                    </div>
                    <div className="col-md-9">
                        <Switch>
                            <Route path={url} component={SlabConfig} exact/>
                            <Route path={`${url}/slabs`} component={SlabConfig}/>
                        </Switch>
                        <div className="d-flex justify-content-center bd-highlight m-3">
                            <SaveButton onClick={this.saveConfig} needssaving={this.props.slabneedsaving}/>
                        </div>
                    </div>
                </div>
            </Router>
        );
    }
}
const TaxConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedTaxConfig);
export default TaxConfig;