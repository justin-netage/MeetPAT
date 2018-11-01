import React, { Component } from 'react';
import { Formik } from 'formik';
import ReactDOM from 'react-dom';
import axios from 'axios';

class Contact extends Component 
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
        initialValues={{ email: '', name: '', message: '' }}
        validate={values => {
          let errors = {};
          if (!values.email) {
            errors.email = 'Your email addres is required.';
          } else if (
            !/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test(values.email)
          ) {
            errors.email = 'Invalid email address';
          }

          if(!values.name) {
            errors.name = 'Your name is required';
          } 

          if(!values.message) {
            errors.message = 'A query is required';
          }

          if(!values.email || !values.name || !values.message)
          {
            document.getElementById('loader').style.display = 'none';
          }

          return errors;
        }}
        onSubmit={(values, { setSubmitting }) => {
          setTimeout(() => {
            // alert(JSON.stringify(values, null, 2));
            axios.post('/api/send-message', {
              name: values.name,
              email: values.email,
              message: values.message,
            })
            .then(function (response) {
              setTimeout(function () {
                  console.log(response);
                  document.getElementById('loader').style.display = 'none';
                  document.getElementById('contact').style.display = 'none';
                  document.getElementById('thank_you').style.display = 'block';

            }, 3000);
            })
            .catch(function (error) {
              console.log(error);
              document.getElementById('loader').style.display = 'none';
            });
            setSubmitting(false);
          }, 400);
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
          /* and other goodies */
        }) => (
        <div className="card">
            <div className="card-header">
                <h1 className="meet_pat">Contact <span style={{color: '#424242'}}>Meet</span><span style={{color: '#039be5'}}>PAT</span></h1>
            </div>
            <div className="card-body">
            <form method="post" action="/send-message" onSubmit={handleSubmit} noValidate>
            <div className="form-group">
                <label htmlFor="clientEmail">Email address</label>
                <input
                type="email"
                name="email"
                className="form-control" id="clientEmail" aria-describedby="emailHelp" placeholder="Enter email"
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.email}
                />
                <div className="invalid-feedback" style={{display: 'block'}}>
                    {errors.email && touched.email && errors.email}
                </div>
            </div>
            <div className="form-group">
                <label htmlFor="clientName">Full Name</label>
                <input
                type="text"
                name="name"
                className="form-control" id="clientName" aria-describedby="nameHelp" placeholder="Enter your full name"
                onChange={handleChange}
                onBlur={handleBlur}
                value={values.name}
                />
                <div className="invalid-feedback" style={{display: 'block'}}>
                    {errors.name && touched.name && errors.name}
                </div>
            </div>
            <div className="form-group">
              <label htmlFor="clientQuery">Query</label>
              <textarea name="message"
                        className="form-control" aria-describedby="queryHelp" id="clientQuery" placeholder="Type your query" cols="30" rows="10"
                        onChange={handleChange}
                        onBlur={handleBlur}
                        value={values.message}
              >
              </textarea>
              <div className="invalid-feedback" style={{display: 'block'}}>
                {errors.message && touched.message && errors.message}
              </div>
            </div>
                <button type="submit" className="btn btn-primary float-right" disabled={isSubmitting} onClick={this.handleClick}>
                Submit
                </button>
            </form>
            </div>
        </div>
        )}
      </Formik>
    )
   }
}
      
  
if (document.getElementById('contact')) {
    ReactDOM.render(<Contact />, document.getElementById('contact'));
}
