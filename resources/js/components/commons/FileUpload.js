import React, { Component } from 'react';
import axios from 'axios';

export default class FileUploadComponent extends Component
{
   constructor(props) {
      super(props);
      this.state ={
        monthconfig: ''
      }
      this.onFormSubmit = this.onFormSubmit.bind(this)
      this.onChange = this.onChange.bind(this)
      this.fileUpload = this.fileUpload.bind(this)
    }
    onFormSubmit(e){
      e.preventDefault() 
      this.fileUpload();
    }
    onChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
            return;
      this.createFile(files[0]);
    }
    createFile(file) {
      let reader = new FileReader();
      reader.onload = (e) => {
        this.setState({
            monthconfig: e.target.result
        })
      };
      reader.readAsText(file);
    }
    fileUpload(){
      const url = '/salaries/upload';
      const formData = {fileToUpload: this.state.monthconfig}
      axios.post(url, formData)
              .then(response => console.log(response))
    }
  
   render()
   {
      return(

         <form onSubmit={this.onFormSubmit}>
            <h4>Upload Monthly Data</h4>
            <input type="file"  onChange={this.onChange} />
            <button type="submit">Upload</button>
        </form>
      )
   }
}