import React, { Component } from 'react';

export default class Accordion extends Component {
    constructor(props){
        super(props);
        this.renderParams = this.renderParams.bind(this);
        this.handleEdit = this.handleEdit.bind(this);
    }
    renderParams(params){
        const pList = Object.entries(params).map(([key,value])=>{
            return (
                <div className="small ml-4 mr-4 border-bottom text-success" key={key}>
                    {key} : {value.toString()}
                </div>
            );
          });
          return (<div>{pList}</div>);
    }
    handleEdit(e){
        this.props.edit(e.target.dataset.id, e.target.dataset.index);
    }

    render() {
        return (
            <div className="accordion" id={this.props.accid}>
                {this.props.data.map((d, index) => (
                <div id={"contact-item-"+index} key={index}>
                    <div className="media text-muted" id={"heading"+index}>
                        <div className="media-body p-0 m-0 small lh-125">
                            <div className="d-flex justify-content-between align-items-center w-100 border-bottom pr-2 bg-light">
                                <button className="btn btn-link" type="button" data-toggle="collapse" data-target={"#collapse"+index} aria-expanded="false" aria-controls={"collapse"+index}>
                                 <span className='small'>{d.name}</span>
                                </button>
                                <a href="javascript:void(0)" onClick={this.handleEdit} data-id={d.id} data-index={index}>Edit</a>
                            </div>
                        </div>
                    </div>
                
                    <div id={"collapse"+index} className="collapse" aria-labelledby={"heading"+index} data-parent={"#"+this.props.accid}>
                        {this.renderParams(d.params)}
                    </div>
                </div>
                ))}
            </div>
        );
    }
}
