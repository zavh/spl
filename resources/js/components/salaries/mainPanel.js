import React, { Component } from 'react';
import axios from 'axios';
export default class MainPanel extends Component {
    componentDidMount(){
        axios.get('/salaries/dbcheck') 
        .then(
            (response)=>{
                console.log(response.data);
            }
        );
    }
    render(){
        return(
            <div>DB Check</div>
        );
    }
}