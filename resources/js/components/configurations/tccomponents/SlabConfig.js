import React, { Component } from 'react';
import FSConfig from './FirstSlabConfig';
import SingleInput from '../../commons/SingleInput';
export default class SlabConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            firstSlabCategories:{},
            firstSlabConfig:{},
            slabs:[],
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
        let slabs = [...this.state.slabs];
        slabs[index].slabval = value;
        this.setState({slabs:slabs});
    }
    onPercChange(value, index){
        let slabs = [...this.state.slabs];
        slabs[index].percval = value;
        this.setState({slabs:slabs});
    }
    deleteSlab(index){
        let slabs = [...this.state.slabs], modslabs = [];
        for(var i=0;i<slabs.length;i++)
            if(i!=index)
                modslabs[modslabs.length] = slabs[i];
        this.setState({slabs:modslabs});
    }
    inputCategory(value){
        this.setState({
            newCategory : value,
            categoryError : [],
        });
    }
    inputSlab(value){
        this.setState({
            newSlab : value,
            slabError : [],
        });
    }
    addSlab(){
        let newslab =[];
        newslab[0]= {
            slabval:this.state.newSlab,
            percval:0
        }
        let slabs = this.state.slabs.concat(newslab);
        this.setState({
            slabs:slabs,
            newSlab:''
        })
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
            if(newkey in this.state.firstSlabCategories){
                let errors = [];
                errors[0] = 'Category '+ value +' already defined',
                this.setState({
                    categoryError:errors
                });
                return;
            }
            let category = {};
            category[newkey] = value;
            let categories = Object.assign(this.state.firstSlabCategories, category);
            this.setState(
                {
                    firstSlabCategories: categories,
                    newCategory: ''
                });
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
                                    Object.keys(this.state.firstSlabCategories).map((category, index)=>{
                                    return(
                                        <option key={index} value={category}>{this.state.firstSlabCategories[category]}</option>
                                    );
                                })}
                            </select>
                        </div>
                    </div>
                    <FSConfig fsdata={this.state.firstSlabConfig} category={this.state.fscSelected}/>
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
                            this.state.slabs.map((slab, index)=>{
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