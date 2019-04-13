import React, { Component } from 'react';
import axios from 'axios';

export default class FileUploadComponent extends Component
{
   constructor(props) {
      super(props);
      this.state ={
        monthconfig: '',
        filename:'Choose Monthly Configuration File',
      }
      this.onFormSubmit = this.onFormSubmit.bind(this);
      this.onChange = this.onChange.bind(this);
      this.fileUpload = this.fileUpload.bind(this);
      this.fileInput = React.createRef();
    }
    onFormSubmit(e){
      e.preventDefault();
      this.fileUpload();
      this.setState({filename:'Choose File'})
    }
    onChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      this.setState({filename:this.fileInput.current.files[0].name})
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
      const formData = {
          fileToUpload: this.state.monthconfig,
          fromYear: this.props.fromYear,
          toYear: this.props.toYear,
          month: this.props.month
        }
      axios.post(url, formData)
              .then(response => console.log(response))
    }
  
   render()
   {
     if(this.props.status == 'success')
      return(
        <div className='input-group input-group-sm col-md-4'>
          <div className='input-group-prepend'>
              <span className="input-group-text">Upload</span>
          </div>
          <div className="custom-file small">
            <input type="file" className="custom-file-input" onChange = {this.onChange} ref={this.fileInput} />
            <label className="custom-file-label" htmlFor="inputGroupFile04">{this.state.filename}</label>
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
