import React, { Component } from 'react';
import FSConfig from './FirstSlabConfig';
import SingleInput from '../../commons/SingleInput';
import { connect } from "react-redux";
import { addSlab, editSlab, deleteSlab, addFSCategory } from "../redux/actions/index";


function mapStateToProps (state)
{
  return { 
      slabs: state.slabs,
      firstSlabCategories: state.firstSlabCategories,
    };
}

function mapDispatchToProps(dispatch) {
    return {
        addSlab: slab => dispatch(addSlab(slab)),
        editSlab: slab => dispatch(editSlab(slab)),
        deleteSlab: index => dispatch(deleteSlab(index)),
        addFSCategory: category => dispatch(addFSCategory(category)),
    };
}

class ConnectedSlabConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            newCategory:'',
            newSlab:'',
            categoryError:[],
            slabError:[],
            fscSelected:0,
        }
        this.inputCategory = this.inputCategory.bind(this);
        this.inputSlab = this.inputSlab.bind(this);
        this.addCategory = this.addCategory.bind(this);
        this.addSlab = this.addSlab.bind(this);
        this.handleFSCChange = this.handleFSCChange.bind(this);
        this.onSlabChange = this.onSlabChange.bind(this);
        this.onPercChange = this.onPercChange.bind(this);
        this.deleteSlab = this.deleteSlab.bind(this);
    }
    onSlabChange(value, index){
        let slabs = [...this.props.slabs];
        let percval = slabs[index].percval;
        this.props.editSlab({slabval:value, percval:percval, index:index});
    }
    onPercChange(value, index){
        let slabs = [...this.props.slabs];
        let slabval = slabs[index].slabval;
        this.props.editSlab({slabval:slabval, percval:value, index:index});
    }
    addSlab(){
        let newslab =[];
        newslab[0]= {
            slabval:this.state.newSlab,
            percval:0
        }
        this.setState({newSlab:''})
        this.props.addSlab(newslab);
    }
    deleteSlab(index){
        this.props.deleteSlab(index);
    }
    //Local State change action //
    inputCategory(value){
        this.setState({
            newCategory : value,
            categoryError : [],
        });
    }
    //Local State change action //
    inputSlab(value){
        this.setState({
            newSlab : value,
            slabError : [],
        });
    }
    
    addCategory(){
        if(this.state.newCategory == ""){
            let errors = [];
            errors[0] = 'No Category value was inserted',
            this.setState({
                categoryError:errors
            });
            return;
        }
        else{
            let value = this.state.newCategory;
            let newkey = value.toLowerCase().replace(' ','_');
            if(newkey in this.props.firstSlabCategories){
                let errors = [];
                errors[0] = 'Category '+ value +' already defined',
                this.setState({
                    categoryError:errors
                });
                return;
            }
            let category = {};
            category[newkey] = value;
            this.setState({newCategory: ''});
            this.props.addFSCategory(category);
        }
    }
    handleFSCChange(e){
        this.setState({
            fscSelected: e.target.value,
        });
    }
    render(){
        return(
            <div className="row m-0 small">
                <div className="col-md-6">
                    <SingleInput 
                        type={'text'}
                        label={'Insert Category'}
                        errors={this.state.categoryError} 
                        onChange={this.inputCategory}
                        value={this.state.newCategory}
                        onInsert={this.addCategory}
                        />
                    <div className="form-group row my-1">
                        <div className="input-group input-group-sm col-md-12">
                            <div className="input-group-prepend">
                                <span className="input-group-text" id="inputGroup-sizing-sm">
                                    Tax Categories
                                </span>
                            </div>
                            <select value={this.state.fscSelected} onChange={this.handleFSCChange} className='form-control'>
                                <option disabled={true} value={0}>Select One</option>
                                {
                                    Object.keys(this.props.firstSlabCategories).map((category, index)=>{
                                    return(
                                        <option key={index} value={category}>{this.props.firstSlabCategories[category]}</option>
                                    );
                                })}
                            </select>
                        </div>
                    </div>
                    <FSConfig category={this.state.fscSelected}/>
                </div>
                <div className="col-md-6">
                    <SingleInput 
                            type={'text'}
                            label={'Add Slab'}
                            errors={this.state.slabError} 
                            onChange={this.inputSlab}
                            value={this.state.newSlab}
                            onInsert={this.addSlab}
                            />
                        {
                            this.props.slabs.map((slab, index)=>{
                                return(
                                    <SlabView 
                                        key={index}
                                        index={index}
                                        onSlabChange={this.onSlabChange}
                                        onPercChange={this.onPercChange}
                                        deleteSlab={this.deleteSlab}
                                        slabval={slab.slabval}
                                        percval={slab.percval}
                                        label={`Slab - ${index + 1}`}
                                    />
                                )
                            })
                        }
                </div>
            </div>

        );
    }
}
function SlabView(props){
    function handleSlabChange(e){
        props.onSlabChange(e.target.value, e.target.dataset.index);        
    }

    function handlePercChange(e){
        props.onPercChange(e.target.value, e.target.dataset.index);        
    }

    function deleteSlab(e){
        props.deleteSlab(e.target.dataset.index);        
    }
    return(
        <div className="form-group row my-1">
            <div className="input-group input-group-sm col-md-12">
                <div className="input-group-prepend">
                    <span className="input-group-text">{props.label}</span>
                </div>
                <input type='text' className="form-control" onChange={handleSlabChange} value={props.slabval} data-index={props.index}/>
                <div className="input-group-append">
                    <span className="input-group-text">Percentage</span>
                </div>
                <input type='number' className="form-control" onChange={handlePercChange} value={props.percval} data-index={props.index}/>
                <div className="input-group-append">
                    <span className="input-group-text">%</span>
                </div>
                <div className="input-group-append">
                    <button className="btn btn-outline-secondary" type="button" id="button-addon2" data-index={props.index} onClick={deleteSlab}>Delete</button>
                </div>
            </div>
        </div>
    );
}

const SlabConfig = connect(mapStateToProps, mapDispatchToProps)(ConnectedSlabConfig);
export default SlabConfig;