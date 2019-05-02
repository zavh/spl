import React, { Component } from 'react';

function Card(props) {
    return (
        <div className="card shadow-sm">
            <div className="card-header d-flex justify-content-between align-items-center m-0 p-0 border-bottom bg-light">
                <span className="mx-2 font-weight-normal text-primary">{props.title}</span>
            </div>

            <div className="card-body p-0 m-0 small">
                {props.children}
            </div>
        </div>
      );
}

export default Card;