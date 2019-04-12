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
     if(this.props.status == 'success')
      return(
        <div className={`input-group input-group-sm col-md-${this.props.colsize}`}>
          <div className={this.props.postype}>
              <span className="input-group-text">{this.props.label}</span>
          </div>
          <div className="custom-file small">
            <input type="file" className="custom-file-input" onChange = {this.onChange}/>
            <label className="custom-file-label" htmlFor="inputGroupFile04">Choose file</label>
          </div>
          <div className="input-group-append">
            <button className="btn btn-outline-primary" type="button" onClick={this.onFormSubmit}>Submit</button>
          </div>
        </div>
      )
      else {
        return null
      }
   }
}
