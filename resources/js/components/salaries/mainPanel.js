import React, { Component } from 'react';
import axios from 'axios';
export default class MainPanel extends Component {
    componentDidMount(){
        axios.get('/salaries/dbcheck')            
        .then(
            (response)=>{
                console.log(response);
            }
        );
    }
    render(){
        return(
            <div>What do you need?</div>
        );
    }
}