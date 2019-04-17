import React, { Component } from 'react';
import axios from 'axios';
export default class Departments extends Component {
    constructor(props){
        super(props);
        this.state = {
            departments:[],
        }
        this.inputChange = this.inputChange.bind(this);
    }

    getDepartments(){
        axios.get('/departments/getall')
            .then((response)=>this.setState({
                departments:[...response.data.departments]
            })
        );
    }

    inputChange(e){
        this.props.onChange(e.target.value);
    }

    componentDidMount(){
        this.getDepartments();
    }
    render() {
        const labelSize = {
            width: this.props.labelSize,
          };
        return (
            <select value={this.props.selected} name={this.props.name} onChange={this.inputChange}>
                <option key='0' value='0' disabled>Select One</option>
                {this.state.departments.map(department => (
                    <option key={department.id} value={department.id}>
                        {department.name}
                    </option>
                ))}
           </select>
        );
    }
}