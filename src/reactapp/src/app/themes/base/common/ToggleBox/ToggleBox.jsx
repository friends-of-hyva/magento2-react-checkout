import React, { useState } from 'react';
import { bool, node } from 'prop-types';
import { ChevronDownIcon, ChevronUpIcon } from 'lucide-react';

import Header from '../Header';

function ToggleBox({ children, title, show, small, hrLine }) {
  const [open, setOpen] = useState(show);

  const arrowContent = (
    <div className="flex items-center justify-center">
      {open ? (
        <ChevronUpIcon className={small ? 'w-5 h-5' : 'w-6 h-6'} />
      ) : (
        <ChevronDownIcon className={small ? 'w-5 h-5' : 'w-6 h-6'} />
      )}
    </div>
  );

  return (
    <div>
      <Header
        small={small}
        extra={arrowContent}
        onClick={() => setOpen(!open)}
        onKeyPress={() => setOpen(!open)}
      >
        {title}
      </Header>
      {hrLine && open && <hr />}
      <div style={{ display: open ? 'block' : 'none' }}>{children}</div>
    </div>
  );
}

ToggleBox.propTypes = {
  show: bool,
  small: bool,
  hrLine: bool,
  title: node.isRequired,
  children: node.isRequired,
};

ToggleBox.defaultProps = {
  show: false,
  small: false,
  hrLine: false,
};

export default ToggleBox;
