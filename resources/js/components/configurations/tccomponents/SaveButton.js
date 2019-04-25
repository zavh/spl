import React from 'react';
export function SaveButton(props){
    function onClick(){
        props.onClick;
    }
    if(props.needssaving)
        return(
            <button type='button' className='btn btn-sm btn-danger' onClick={props.onClick} disabled={false}>
                Save Configuration
            </button>
            )
    else
        return(
            <button type='button' className='btn btn-sm btn-secondary' onClick={onClick} disabled={true}>
                Save Configuration
            </button>
            )
}