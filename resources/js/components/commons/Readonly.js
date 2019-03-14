import React from 'react';

function Readonly(props) {
    const labelSize = {
        width: props.labelSize,
      };
    return (
        <div className="form-group row my-1">
        <div className="input-group input-group-sm col-md-12">
            <div className="input-group-prepend">
                <span className="input-group-text" id="inputGroup-sizing-sm" style={labelSize}>{props.label}</span>
            </div>
            <div className="form-control">
                {props.value}
            </div>
        </div>
    </div>
      );
}

export default Readonly;