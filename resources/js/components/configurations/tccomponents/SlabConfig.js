import React, { Component } from 'react';
import SingleInput from '../../commons/SingleInput';
export default class SlabConfig extends Component{
    constructor(props){
        super(props);
        this.state = {
            firstSlabCategories:{},
            newCategory:'',
            categoryError:[],
        }
        this.inputCategory = this.inputCategory.bind(this);
        this.addCategory = this.addCategory.bind(this);
        this.handleCategoryChange = this.handleCategoryChange.bind(this);
    }
    inputCategory(value){
        this.setState({
            newCategory : value,
            categoryError : [],
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
        console.log(this.state);
    }
    handleCategoryChange(){
        console.log(this.state);
    }
    render(){
        return(
            <div>
                <SingleInput 
                    type={'text'}
                    label={'Insert Category'}
                    errors={this.state.categoryError} 
                    onChange={this.inputCategory}
                    value={this.state.newCategory}
                    onInsert={this.addCategory}
                    />
                <select value={0} onChange={this.handleCategoryChange}>
                    <option disabled={true} value={0}>Select One</option>
                {
                    Object.keys(this.state.firstSlabCategories).map((category, index)=>{
                    return(
                        <option key={index} value={category}>{this.state.firstSlabCategories[category]}</option>
                    );
                })}
                </select>
            </div>

        );
    }
}