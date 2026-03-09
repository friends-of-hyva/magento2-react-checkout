import React from 'react';
import { bool, func, shape } from 'prop-types';
import { PlusIcon } from 'lucide-react';

import Button from '../../../../code/common/Button';
import { __ } from '../../../../../i18n';
import { _emptyFunc } from '../../../../../utils';
import useAppContext from '../../../../../hooks/useAppContext';

function CreateNewAddressLink({ actions, forceHide }) {
  const { isLoggedIn } = useAppContext();

  if (!isLoggedIn || forceHide) {
    return null;
  }

  return (
    <div className="mt-6">
      <Button variant="warning" click={actions.click}>
        <PlusIcon className="w-6 h-6" />
        <span className="text-xs">{__('New Address')}</span>
      </Button>
    </div>
  );
}

CreateNewAddressLink.propTypes = {
  forceHide: bool,
  actions: shape({
    click: func,
  }),
};

CreateNewAddressLink.defaultProps = {
  forceHide: false,
  actions: { click: _emptyFunc() },
};

export default CreateNewAddressLink;
