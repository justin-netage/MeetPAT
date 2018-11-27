import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Table from 'react-bootstrap/lib/Table';


class AllUsers extends Component {
    constructor(props, context) {
        super(props, context);
        this.state={
            data:[]
        }
    }

    componentDidMount(){
        axios.get('/api/meetpat-admin/users')
        .then(data => this.setState({data:data}));
          }

    render() {

        return (
            <Table responsive>
                <thead>
                    <tr>
                    <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    {/* {this.state.data.map(user => {
                        <li>{user.name}</li>
                    }
                        
                    )} */}
                    {/* { JSON.stringify(this.state.data.data)} */}
                </tbody>
                </Table>
        );
      }
      
  }
  
  if (document.getElementById('users')) {
    ReactDOM.render(<AllUsers />, document.getElementById('users'));
}