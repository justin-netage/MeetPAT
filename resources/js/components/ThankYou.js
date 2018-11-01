import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class Thankyou extends Component
{

    render() {
        return (

            <div className="card">
            <div className="card-header">
                <h1 className="display">Contact <span style={{color: '#3C3C3C'}}>Meet</span><span style={{color: '#008DFF'}}>PAT</span></h1>
            </div>
            <div className="card-body">
                <p>Thank You for contacting <span style={{color: '#3C3C3C'}}>Meet</span><span style={{color: '#008DFF'}}>PAT</span> !</p>
                <p>Our team will review your query and get back to as soon as possible.</p>
                <p>If you have not recieved a response from us in 48hrs please email us directly at <a href="mailto:info@meetpat.co.za?Subject=Online%20Query%20%2D%20MeetPAT" target="_top">info@meetpat.co.za</a>
                </p>
            </div>
            </div>
        )
    }
}

if (document.getElementById('thank_you')) {
    document.getElementById('thank_you').style.display = 'none';
    ReactDOM.render(<Thankyou />, document.getElementById('thank_you'));
}