/* eslint-disable react/no-array-index-key */
import React from 'react';
import { arrayOf, func, node, shape, string } from 'prop-types';
import { ShieldCheckIcon, PencilIcon } from 'lucide-react';

import Card from '../../common/Card';
import { __ } from '../../../../../i18n';
import { _isObjEmpty } from '../../../../../utils';
import useAppContext from '../../../../../hooks/useAppContext';

function AddressCard({
  title,
  actions,
  billingSameCheckbox,
  address: { id, address },
}) {
  const { isLoggedIn, customerAddressList } = useAppContext();

  return (
    <Card bg="dark" classes="card-interactive">
      <div className="flex justify-between">
        <h3 className="text-sm font-bold text-black">{title}</h3>
        <ShieldCheckIcon className="w-6 h-6 -m-2 text-green-700" />
      </div>
      <hr />
      <ul className="pt-3">
        {address.map((addrAttr, index) => (
          <li
            key={`${id}_${addrAttr}_${index}`}
            className="text-sm italic text-black"
          >
            {addrAttr}
          </li>
        ))}
      </ul>
      {billingSameCheckbox}
      <div className="flex items-center justify-between h-12 px-3 mt-3 -mx-4 -mb-4 rounded-b-sm bg-container-darker">
        {isLoggedIn && !_isObjEmpty(customerAddressList) && (
          <span className="text-xs italic font-semibold capitalize text-secondary-lighter">
            {customerAddressList[id]
              ? __('FROM ADDRESS BOOK')
              : __('NEW ADDRESS')}
          </span>
        )}
        <div className="flex items-center justify-end flex-1">
          <button
            type="button"
            onClick={actions.performAddressEdit}
            className="px-2 py-1 btn-secondary btn"
          >
            <PencilIcon className="w-6 h-6 pr-1" />
            <span className="text-xs">{__('Edit')}</span>
          </button>
        </div>
      </div>
    </Card>
  );
}

AddressCard.propTypes = {
  title: string,
  billingSameCheckbox: node,
  actions: shape({ performAddressEdit: func }).isRequired,
  address: shape({ id: string, address: arrayOf(string) }).isRequired,
};

AddressCard.defaultProps = {
  title: '',
  billingSameCheckbox: null,
};

export default AddressCard;
