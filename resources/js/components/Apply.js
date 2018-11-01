import React, { Component } from 'react';
import { Formik } from 'formik';
import ReactDOM from 'react-dom';
import axios from 'axios';

class Apply extends Component
{
    constructor(props) {
        super(props);
        this.state = {isToggleOn: true};
    
        // This binding is necessary to make `this` work in the callback
        this.handleClick = this.handleClick.bind(this);
      }
      handleClick() {
        this.setState(prevState => ({
          isToggleOn: !prevState.isToggleOn
        }));
        document.getElementById('loader').style.display = 'block';
    
      }

      render() {

        return (
            <Formik 
                initialValues={{ name: '',
                                 surname: '',
                                 contact_number: '',
                                 contact_email: '',
                                 street_address: '',
                                 city: '',
                                 province: '',
                                 postal_code: '',
                                 }}
                validate={values => {
                    let errors = {};

                    if(!values.name) {
                        errors.name = 'Your name is required.'
                    }

                    if(!values.surname) {
                        errors.surname = 'Your surname is required.'

                    }
                    
                    if(!values.contact_number) {
                        errors.contact_number = 'Your contact number is required.'

                    }
                    
                    if(!values.contact_email) {
                        errors.contact_email = 'Your contact email is required.'

                    }

                    if(!values.street_address) {
                        errors.street_address = 'Your street address is required.'

                    }

                    if(!values.city) {
                        errors.city = 'Your city is required.'

                    }

                    if(!values.province) {
                        errors.province = 'Your province is required.'

                    }

                    if(!values.postal_code) {
                        errors.postal_code = 'Your postal code is required.'

                    }

                    return errors;
                }}

                onSubmit={(values, { setSubmitting}) => {
                    axios.post('/api/send-application', {
                        name: values.name,
                        surname: values.surname,
                        contact_number: values.contact_number,
                        contact_email: values.contact_email,
                        street_address: values.street_address,
                        city: values.city,
                        province: values.province,
                        postal_code: values.postal_code,
                      })
                      .then(function (response) {
                        alert(JSON.stringify(values, null, 2));
                      })
                      .catch(function (error) {
                          console.log(error);
                      })
                }}
            >
            {({
                values,
                errors,
                touched,
                handleChange,
                handleBlur,
                handleSubmit,
                isSubmitting,

            }) => (
                <div className="card">
                    <div className="card-header">
                        <h1 className="meet_pat"><span style={{color: '#424242'}}>Meet</span><span style={{color: '#039be5'}}>PAT</span> Application Form</h1>
                    </div>
                    <div className="card-body"></div>
                </div>
            )}
            </Formik>
        )
      }
}

if (document.getElementById('apply')) {
    ReactDOM.render(<Apply />, document.getElementById('apply'));
}