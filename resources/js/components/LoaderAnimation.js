import React from 'react';
import { css } from 'react-emotion';
// First way to import
import { RingLoader } from 'react-spinners';
import ReactDOM from 'react-dom';
 
const override = css`
    display: block;
    margin: 0 auto;
    border-color: red;
`;
 
class AwesomeComponent extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: true
    }
  }
  render() {
    return (
      <div className='sweet-loading'>
        <RingLoader
          className={override}
          sizeUnit={"px"}
          size={150}
          color={'#008DFF'}
          loading={this.state.loading}
        />
      </div> 
    )
  }
}

if (document.getElementById('loader')) {
    document.getElementById('loader').style.display = 'none';
    ReactDOM.render(<AwesomeComponent />, document.getElementById('loader'));
}
